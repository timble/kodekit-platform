<?php
class ComLanguagesDatabaseRowLanguage extends KDatabaseRowDefault
{
    public function save()
    {
        if($this->isNew())
        {
            $tables   = $this->getService('com://admin/languages.model.tables')->getList();
            $database = $this->getTable()->getDatabase();
            $prefix   = $database->getTablePrefix();
            
            foreach($tables as $table)
            {
                $table_name = strtolower($this->iso_code).'_'.$table->table_name;

                // TODO: $syncing = $this->_db->setSyncing(false);
                
                // Add language specific table and copy the content of the original table.
                $database->execute('CREATE TABLE '.$database->quoteIdentifier($prefix.$table_name).' LIKE '.$database->quoteIdentifier($prefix.$table->table_name));
                
                $select = $this->getService('koowa:database.query.select')
                    ->table($table->table_name);
                
                $insert = $this->getService('koowa:database.query.insert')
                    ->table($table_name)
                    ->values($select);
                
                $database->insert($insert);

                // Add items to the items table.
                $columns = array(
                    'iso_code' => ':iso_code',
                    'table' => ':table',
                    'row' => 'tbl.'.$table->unique_column,
                    'title' => 'tbl.'.$table->title_column,
                    'created_on' => 'items.created_on',
                    'created_by' => 'items.created_by',
                    'modified_on' => 'items.modified_on',
                    'modified_by' => 'items.modified_by',
                    'status' => ':status',
                    'original' => ':original_column'
                );
                
                $select = $this->getService('koowa:database.query.select')
                    ->columns($columns)
                    ->table(array('tbl' => $table_name))
                    ->join(array('items' => 'languages_items'), 'items.row = tbl.'.$table->unique_column.' AND items.table = :table AND items.original = :original_join')
                    ->bind(array(
                        'iso_code' => $this->iso_code,
                        'table' => $table->table_name,
                        'status' => ComLanguagesDatabaseRowItem::STATUS_MISSING,
                        'original_column' => 0,
                        'original_join' => 1
                    ));
                
                $insert = $this->getService('koowa:database.query.insert')
                    ->table('languages_items')
                    ->columns(array_keys($columns))
                    ->values($select);
                
                $database->insert($insert);
                
                // TODO: $this->_db->setSyncing($syncing);
            }
        }
        
        return parent::save();
    }
    
    public function __set($column, $value)
    {
        switch($column)
        {
            case 'iso_code_lang':
                list($language, $country) = explode('-', $this->iso_code, 2);
                $this->iso_code = $value.'-'.$country;
                return;
                
            case 'iso_code_country':
                list($language, $country) = explode('-', $this->iso_code, 2);
                $this->iso_code = $language.'-'.$value;
                return;
        }
        
        parent::__set($column, $value);
    }
    
    public function __get($column)
	{
    	    switch($column)
    	    {
    	        case 'iso_code_lang':
    	            list($language, $country) = explode('-', $this->iso_code, 2);
    	            return $language;
    	        
    	        case 'iso_code_country':
    	            list($language, $country) = explode('-', $this->iso_code, 2);
    	            return $country;
    	            	        
    	        case 'primary':
    	            if(empty($this->_data['primary']))
    	            {
    	                $primary = JComponentHelper::getParams('com_languages')->getValue('primary_language', 'en-GB');
    	                $this->primary = $this->iso_code == $primary;
    	            }
    	            break;
	    }
	
		return parent::__get($column);
	}
}