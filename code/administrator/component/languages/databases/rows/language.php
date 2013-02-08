<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Language Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesDatabaseRowLanguage extends KDatabaseRowTable
{
    public function save()
    {
        $modified = $this->isModified('enabled');
        $result   = parent::save();
        
        if($this->getStatus() == KDatabase::STATUS_UPDATED && $modified && $this->enabled && $this->application == 'site')
        {
            $tables   = $this->getService('com://admin/languages.model.tables')->getRowset();
            $database = $this->getTable()->getAdapter();
            $prefix   = $database->getTablePrefix();
            
            foreach($tables as $table)
            {
                $table_name = strtolower($this->iso_code).'_'.$table->name;
                
                // Add language specific table and copy the content of the original table.
                $database->execute('CREATE TABLE '.$database->quoteIdentifier($prefix.$table_name).' LIKE '.$database->quoteIdentifier($prefix.$table->name));
                
                $select = $this->getService('koowa:database.query.select')
                    ->table($table->name);
                
                $insert = $this->getService('koowa:database.query.insert')
                    ->table($table_name)
                    ->values($select);
                
                $database->insert($insert);

                // Add items to the translations table.
                $columns = array(
                    'iso_code' => ':iso_code',
                    'table' => ':table',
                    'row' => 'tbl.'.$table->unique_column,
                    'status' => ':status',
                    'original' => ':original'
                );
                
                $select = $this->getService('koowa:database.query.select')
                    ->columns($columns)
                    ->table(array('tbl' => $table_name))
                    ->bind(array(
                        'iso_code' => $this->iso_code,
                        'table' => $table->name,
                        'status' => ComLanguagesDatabaseRowTranslation::STATUS_MISSING,
                        'original' => 0
                    ));
                
                $insert = $this->getService('koowa:database.query.insert')
                    ->table('languages_translations')
                    ->columns(array_keys($columns))
                    ->values($select);
                
                $database->insert($insert);
            }
        }
        
        return $result;
    }
}