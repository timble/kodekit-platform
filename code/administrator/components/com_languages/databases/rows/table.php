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
 * Table Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesDatabaseRowTable extends KDatabaseRowDefault
{
    public function save()
    {
        $result = parent::save();
        
        if($this->getStatus() == KDatabase::STATUS_CREATED)
        {
            $database  = $this->getTable()->getDatabase();
            $prefix    = $database->getTablePrefix();
            $languages = JFactory::getApplication()->getLanguages();
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
                    
                    $status   = ComLanguagesDatabaseRowItem::STATUS_MISSING;
                    $original = 0;
                            
                }
                else
                {
                    $status   = ComLanguagesDatabaseRowItem::STATUS_COMPLETED;
                    $original = 1;
                }
                
                // Add items to the items table.
                $select = $this->getService('koowa:database.query.select')
                    ->columns(array(
                        'iso_code' => ':iso_code',
                        'table' => ':table',
                        'row' => $this->unique_column,
                        'title' => $this->title_column,
                        'created_on' => ':created_on',
                        'created_by' => ':created_by',
                        'status' => ':status',
                        'original' => ':original'
                    ))
                    ->table($this->name)
                    ->bind(array(
                        'iso_code' => $language->iso_code,
                        'table' => $this->name,
                        'created_on' => gmdate('Y-m-d H:i:s'),
                        'created_by' => JFactory::getUser()->id,
                        'status' => $status,
                        'original' => $original
                    ));
                
                if($this->table_column && $this->table_value)
                {
                    $select->where($this->table_column.' = :table_column')
                        ->bind(array('table_column' => $this->table_value));
                }
                
                $query = $this->getService('koowa:database.query.insert')
                    ->table('languages_items')
                    ->columns(array(
                        'iso_code',
                        'table',
                        'row',
                        'title',
                        'created_on',
                        'created_by',
                        'status',
                        'original'
                    ))
                    ->values($select);
                
                $database->execute($query);
            }
        }
        
        return $result;
    }
}