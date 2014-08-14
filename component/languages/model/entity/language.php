<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Language Model Entity
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class ModelEntityLanguage extends Library\ModelEntityRow
{
    public function save()
    {
        $modified = $this->isModified('enabled');
        $result   = parent::save();
        
        if($this->getStatus() == self::STATUS_UPDATED && $modified && $this->enabled && $this->application == 'site')
        {
            $tables   = $this->getObject('com:languages.model.tables')->fetch();
            $database = $this->getTable()->getAdapter();
            
            foreach($tables as $table)
            {
                $table_name = strtolower($this->iso_code).'_'.$table->name;
                
                // Add language specific table and copy the content of the original table.
                $database->execute('CREATE TABLE '.$database->quoteIdentifier($table_name).' LIKE '.$database->quoteIdentifier($table->name));
                
                $select = $this->getObject('lib:database.query.select')
                    ->table($table->name);
                
                $insert = $this->getObject('lib:database.query.insert')
                    ->table($table_name)
                    ->values($select);
                
                $database->insert($insert);

                // Add items to the translations table.
                $columns = array(
                    'iso_code'  => ':iso_code',
                    'table'     => ':table',
                    'row'       => 'tbl.'.$table->unique_column,
                    'status'    => ':status',
                    'original'  => ':original'
                );
                
                $select = $this->getObject('lib:database.query.select')
                    ->columns($columns)
                    ->table(array('tbl' => $table_name))
                    ->bind(array(
                        'iso_code'  => $this->iso_code,
                        'table'     => $table->name,
                        'status'    => ModelEntityTranslation::STATUS_MISSING,
                        'original'  => 0
                    ));
                
                $insert = $this->getObject('lib:database.query.insert')
                    ->table('languages_translations')
                    ->columns(array_keys($columns))
                    ->values($select);
                
                $database->insert($insert);
            }
        }
        
        return $result;
    }
}