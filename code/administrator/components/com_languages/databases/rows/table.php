<?php
class ComLanguagesDatabaseRowTable extends KDatabaseRowDefault
{
    public function save()
    {
        if($this->isNew())
        {
            $database  = $this->getTable()->getDatabase();
            $languages = $this->getService('com://admin/languages.model.languages')->getList();
            $primary   = $this->getService('com://admin/languages.config.language')->getPrimary();

            foreach($languages as $language)
            {
                $table_name = $this->table_name;
                
                if($language->iso_code != $primary->iso_code)
                {
                    $table_name = strtolower($language->iso_code).'_'.$this->table_name;
                    
                    // Create language specific table.
                    $query = 'CREATE TABLE '.$database->quoteIdentifier($database->getTablePrefix().$table_name).
                        ' LIKE '.$database->quoteIdentifier($database->getTablePrefix().$this->table_name);
                    $database->execute($query);
                    
                    // Copy content of original table into the language specific one.
                    $query = $this->getService('koowa:database.query.insert')
                        ->table($table_name)
                        ->values($this->getService('koowa:database.query.select')->table($this->table_name));
                    $database->execute($query);
                    
                    $status   = ComLanguagesDatabaseRowItem::STATUS_MISSING;
                    $original = 0;
                            
                }
                else
                {
                    $status   = ComLanguagesDatabaseRowItem::STATUS_COMPLETED;
                    $original = 1;
                }
                
                // Add items to languages_items table.
                $subquery = $this->getService('koowa:database.query.select')
                    ->columns(array(
                        'iso_code' => ':iso_code',
                        'table' => ':table_name',
                        'row' => ':unique_column',
                        'title' => ':title_column',
                        'created_on' => 'NOW()',
                        'created_by' => 0,
                        'modified_on' => 'NOW()',
                        'modified_by' => 0,
                        'status' => ':status',
                        'original' => ':original'
                    ))
                    ->table($table_name)
                    ->bind(array(
                        'iso_code' => $language->iso_code,
                        'table_name' => $this->table_name,
                        'unique_column' => $this->unique_column,
                        'title_column' => $this->title_column,
                        'status' => $status,
                        'original' => $original
                    ));
                
                $query = $this->getService('koowa:database.query.insert')
                    ->table('languages_items')
                    ->columns(array(
                        'iso_code',
                        'table',
                        'row',
                        'title',
                        'created_on',
                        'created_by',
                        'modified_on',
                        'modified_by',
                        'status',
                        'original'
                    ))
                    ->values($subquery);
                
                $database->execute($query);
            }
        }
        
        return parent::save();
    }
    
    protected function _findUniqueColumn()
    {
        $result = '';
        $schema = $this->getTable()->getDatabase()->getTableSchema($this->table_name);
        
        // Find the primary key.
        foreach($schema->columns as $column)
        {
            if($column->primary)
            {
                $result = $column->name;
                break;
            }
        }
        
        // If no primary key was found, try an autoinc column.
        if(!$result)
        {
            foreach($schema->columns as $column)
            {
                if($column->autoinc)
                {
                    $result = $column->name;
                    break;
                }
            }
        }
        
        // If no autoinc column was found, try a unique key.
        if(!$result)
        {
            foreach($schema->columns as $column)
            {
                if($column->unique)
                {
                    $result = $column->name;
                    break;
                }
            }
        }
        
        return $result;
    }
    
    protected function _findTitleColumn()
    {
        $result = '';
        $schema = $this->getTable()->getDatabase()->getTableSchema($this->table_name);
        
        // Find the title column, based on a list of typical names.
        $titles = array('title', 'name', 'alias', 'text', 'dmname');
        
        foreach($titles as $title)
        {
            if(array_key_exists($title, $schema->columns))
            {
                $result = $title;
                break;
            }
        }
        
        // If no suitable column was found, find a char or varchar field to use as title.
        if(!$result)
        {
            foreach($schema->columns as $column)
            {
                if(stripos($column->type, 'char'))
                {
                    $result = $column->name;
                    break;
                }
            }
        }
        
        // If still nothing was found, try text fields.
        if(!$result)
        {
            foreach($schema->columns as $column)
            {
                if(stripos($column->type, 'text'))
                {
                    $result = $column->name;
                    break;
                }
            }
        }
        
        return $result;
    }
    
    public function __get($key)
    {
        if(($key == 'unique_column' || $key == 'title_column') && !isset($this->data['unique_column'])) {
            $this->unique_column = $this->_findUniqueColumn();
        }
        
        if($key == 'title_column' && !isset($this->data['title_column'])) {
            $this->title_column = $this->_findTitleColumn();
        }
        
        if($key == 'description' && !isset($this->_data['description']))
        {
            $schema = $this->getTable()->getDatabase()->getTableSchema($this->table_name);
            $this->description = $schema->description;
        }
        
        return parent::__get($key);
    }
}