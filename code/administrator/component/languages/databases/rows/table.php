<?php
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Table Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesDatabaseRowTable extends KDatabaseRowTable
{
    public function save()
    {
        $modified = $this->isModified('enabled');
        $result   = parent::save();
        
        if($this->getStatus() == KDatabase::STATUS_UPDATED && $modified && $this->enabled)
        {
            $database  = $this->getTable()->getAdapter();
            $prefix    = $database->getTablePrefix();
            $languages = $this->getService('application.languages');
            $primary   = $languages->getPrimary();

            foreach($languages as $language)
            {
                if($language->id != $primary->id)
                {
                    $table = strtolower($language->iso_code).'_'.$this->name;
                    
                    // Create language specific table.
                    $query = 'CREATE TABLE '.$database->quoteIdentifier($prefix.$table).
                        ' LIKE '.$database->quoteIdentifier($prefix.$this->name);
                    $database->execute($query);
                    
                    // Copy content of original table into the language specific one.
                    $query = $this->getService('koowa:database.query.insert')
                        ->table($table)
                        ->values($this->getService('koowa:database.query.select')->table($this->name));
                    $database->execute($query);
                    
                    $status   = ComLanguagesDatabaseRowTranslation::STATUS_MISSING;
                    $original = 0;
                            
                }
                else
                {
                    $status   = ComLanguagesDatabaseRowTranslation::STATUS_COMPLETED;
                    $original = 1;
                }
                
                // Add items to the translations table.
                $select = $this->getService('koowa:database.query.select')
                    ->columns(array(
                        'iso_code' => ':iso_code',
                        'table' => ':table',
                        'row' => $this->unique_column,
                        'status' => ':status',
                        'original' => ':original'
                    ))
                    ->table($this->name)
                    ->bind(array(
                        'iso_code' => $language->iso_code,
                        'table' => $this->name,
                        'status' => $status,
                        'original' => $original
                    ));
                
                $query = $this->getService('koowa:database.query.insert')
                    ->table('languages_translations')
                    ->columns(array('iso_code', 'table', 'row', 'status', 'original'))
                    ->values($select);
                
                $database->execute($query);
            }
        }
        
        return $result;
    }
}