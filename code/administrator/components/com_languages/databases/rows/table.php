<?php
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
                    $table = strtolower($language->iso_code).'_'.$this->table;
                    
                    // Create language specific table.
                    $query = 'CREATE TABLE '.$database->quoteIdentifier($prefix.$table).
                        ' LIKE '.$database->quoteIdentifier($prefix.$this->table);
                    $database->execute($query);
                    
                    // Copy content of original table into the language specific one.
                    $query = $this->getService('koowa:database.query.insert')
                        ->table($table)
                        ->values($this->getService('koowa:database.query.select')->table($this->table));
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
                        'languages_language_id' => ':languages_language_id',
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
                        'languages_language_id' => $language->id,
                        'table' => $this->name,
                        'created_on' => gmdate('Y-m-d H:i:s'),
                        'created_by' => JFactory::getUser()->id,
                        'status' => $status,
                        'original' => $original
                    ));
                
                $query = $this->getService('koowa:database.query.insert')
                    ->table('languages_items')
                    ->columns(array(
                        'languages_language_id',
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