<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Table Model Entity
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Languages
 */
class ModelEntityTable extends Library\ModelEntityRow
{
    public function save()
    {
        $modified = $this->isModified('enabled');
        $result   = parent::save();

        if($this->getStatus() == self::STATUS_UPDATED && $modified && $this->enabled)
        {
            $database  = $this->getTable()->getDriver();
            $languages = $this->getObject('languages');
            $default   = $languages->getDefault();

            foreach($languages as $language)
            {
                if($language->id != $default->id)
                {
                    $table = strtolower($language->iso_code).'_'.$this->name;

                    // Create language specific table.
                    $query = 'CREATE TABLE '.$database->quoteIdentifier($table).
                        ' LIKE '.$database->quoteIdentifier($this->name);
                    $database->execute($query);

                    // Copy content of original table into the language specific one.
                    $query = $this->getObject('lib:atabase.query.insert')
                        ->table($table)
                        ->values($this->getObject('lib:database.query.select')->table($this->name));
                    $database->execute($query);

                    $status   = ModelEntityTranslation::STATUS_MISSING;
                    $original = 0;
                }
                else
                {
                    $status   = ModelEntityTranslation::STATUS_COMPLETED;
                    $original = 1;
                }

                // Add items to the translations table.
                $select = $this->getObject('lib:database.query.select')
                    ->columns(array(
                        'iso_code'  => ':iso_code',
                        'table'     => ':table',
                        'row'       => $this->unique_column,
                        'status'    => ':status',
                        'original'  => ':original'
                    ))
                    ->table($this->name)
                    ->bind(array(
                        'iso_code'  => $language->iso_code,
                        'table'     => $this->name,
                        'status'    => $status,
                        'original'  => $original
                    ));

                $query = $this->getObject('lib:database.query.insert')
                    ->table('languages_translations')
                    ->columns(array('iso_code', 'table', 'row', 'status', 'original'))
                    ->values($select);

                $database->execute($query);
            }
        }

        return $result;
    }
}