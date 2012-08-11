<?php

class ComLanguagesControllerTable extends ComDefaultControllerDefault
{
    public function execute($name, KCommandContext $context)
    {
        // Override add action execution to be able to add multiple tables.
        if($name == 'add')
        {
            $result = true;
            $tables = KConfig::unbox($this->getRequest()->table_name);
            
            foreach($tables as $table)
            {
                $clone = clone $context;
                $context->data->table_name = $table;
                
                if(parent::execute($name, $context) === false)
                {
                    $result = false;
                    break;
                }
            }
        } else $result = parent::execute($name, $context);
        
        return $result;
    }
    
    /*protected function _actionAdd(KCommandContext $context)
    {exit('here');
        $tables = KConfig::unbox($this->getRequest()->table_name);
        foreach($tables as $table)
        {
            $data = $this->getModel()->reset()->getItem();
            $data->table_name = $table;
            
            if($data->save() === false)
		    {
			    $error = $data->getStatusMessage();
		        $context->setError(new KControllerException(
		           $error ? $error : 'Add Action Failed', KHttpResponse::INTERNAL_SERVER_ERROR
		        ));
                break;
		    }
        }
        
        if(!$context->hasError()) {
            $context->status = KHttpResponse::CREATED;
        }
    }*/
    
	public function add()
	{
		$model	= KFactory::get('admin::com.nooku.model.tables');
		$tbl	= KFactory::get('admin::com.nooku.table.tables');

		$cid 	= KInput::get('cid', 'post', 'array.cmd');
		$tables	= $model->getTableData($cid);
		
		foreach($tables as $table) {
			$tbl->insert($table);
		}
		
		$this->setRedirect('view=tables');
	}
	
	public function syncInsert()
	{
		$db        = KFactory::get('lib.joomla.database');
		
		$nooku     = KFactory::get('admin::com.nooku.model.nooku');
		$languages = $nooku->getLanguages();
		
		unset($languages[$nooku->getPrimaryLanguage()->iso_code]);
		
		//We need to load all the languages
		$db->select($db->getQuery()
				->select(array('*', 'nooku_table_id AS id'))
				->from('nooku_tables')
				->order('table_name')
		);
		
		$tables = $db->loadObjectList('table_name');
			
		// walk through nooku tables
		foreach($tables as $table)
		{
		    // walk through nooku languages
		    foreach($languages as $language)
		    {	
		    	// select missing records
    		    $query = "SELECT id FROM #__".$table->table_name." WHERE ".$db->nameQuote($table->unique_column)." NOT IN" 
    		    	    ." (SELECT id FROM ".$db->nameQuote("#__".strtolower($language->iso_code)."_".$table->table_name).")";
    		   	$db->setQuery($query);
    		   	$missing = $db->loadObjectList();

    		    // insert missing record into Nooku table	
    		  	foreach($missing as $record)
    		    {
    		       	$query = "INSERT INTO ".$db->nameQuote("#__".strtolower($language->iso_code)."_".$table->table_name)
    		       			." ( SELECT * FROM #__".$table->table_name." WHERE ".$db->nameQuote($table->unique_column)." = ".$db->Quote($record->id).")";
    		       	$db->setQuery($query);
    		       	
    		       	if($db->Query()) {
    		         	KFactory::get('lib.joomla.application')->enqueueMessage("Nooku Sync - copied row with ".$table->unique_column.":".$record->id." from table ".$table->table_name." in ".strtolower($language->iso_code)."_".$table->table_name);
    		        }     
		        }
		    }    
		}
	}
	
	public function syncDelete()
	{
		$db        = KFactory::get('lib.joomla.database');
		
		$nooku     = KFactory::get('admin::com.nooku.model.nooku');
		$languages = $nooku->getLanguages();
		
		unset($languages[$nooku->getPrimaryLanguage()->iso_code]);
		
		//We need to load all the languages
		$db->select($db->getQuery()
				->select(array('*', 'nooku_table_id AS id'))
				->from('nooku_tables')
				->order('table_name')
		);
		
		$tables = $db->loadObjectList('table_name');
			
		// walk through nooku tables
		foreach($tables as $table)
		{
		    // walk through nooku languages
		    foreach($languages as $language)
		    {	
		    	// select missing records
    		    // select missing records
    		    $query = "SELECT id FROM ".$db->nameQuote("#__".strtolower($language->iso_code)."_".$table->table_name)." WHERE ".$db->nameQuote($table->unique_column)." NOT IN" 
    		    	    ." (SELECT id FROM #__".$table->table_name.")";
    		   	$db->setQuery($query);
    		   	$missing = $db->loadObjectList();

    		    // insert missing record into Nooku table	
    		  	foreach($missing as $record)
    		    {
    		       	$query = "DELETE FROM ".$db->nameQuote("#__".strtolower($language->iso_code)."_".$table->table_name). "WHERE ".$db->nameQuote($table->unique_column)." = ".$db->Quote($record->id);
    		       	$db->setQuery($query);
    		       	
    		       	if($db->Query()) {
    		         	KFactory::get('lib.joomla.application')->enqueueMessage("Nooku Sync - deleted row with ".$table->unique_column.":".$record->id." from table ".strtolower($language->iso_code)."_".$table->table_name);
    		        }     
		        }
		    }    
		}
	}
}