<?php

class ComLanguagesModelLangpacks extends KModelAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->getState()
			->insert('limit'       , 'int', 0)
			->insert('offset'      , 'int', 0)
			->insert('direction'   , 'word', 'asc')
			->insert('application' , 'cmd', 'site')
			->insert('default'	   , 'boolean', false, true)
			->insert('translatable', 'boolean')
			->insert('name'	       , 'com://admin/languages.filter.iso', null, true);
	}

	public function getItem()
	{
		if (!isset($this->_item))
		{
			if($this->_state->isUnique())
            {
            	$state = $this->_state;
            	
				//Get application information
				$client	= JApplicationHelper::getClientInfo($state->application, true);
            	if (!empty($client)) 
            	{
					//Get the path
            	    $default = JComponentHelper::getParams('com_languages')->get($client->name, 'en-GB');
				 
				    if ($state->default) {
					    $state->language = $default;
				    }
				
				     $path  = $client->path.'/language/'.$state->name;
				    
				    //Create the row
				    $data = array(
						'path'         => $path,
						'application'  => $client->name
				    );

				    $row = $this->getService('com://admin/languages.database.row.langpack', array('data' => $data));				
				    $row->default = $row->language == $default;

				    $this->_item = $row;
				}
				else throw new KModelException('Invalid application');
            }
		}

		return parent::getItem();
	}

	public function getList()
	{
		if (!isset($this->_list))
		{
			$state     = $this->_state;
			$langpacks = array();

			foreach((array) KConfig::unbox($state->application) as $application)
            {
                $client	= JApplicationHelper::getClientInfo($application, true);
			    if(!empty($client)) 
			    {
			        $default = JComponentHelper::getParams('com_languages')->get($client->name, 'en-GB');
			    
			        //Find the languages
                    $path = $client->path.'/language';

                    foreach(new DirectoryIterator($path) as $folder)
                    {
                        if($folder->isDir())
                        {
                           if(file_exists($folder->getRealPath().'/'.$folder->getFilename().'.xml')) 
                           { 
                               $langpacks[$folder->getFilename()] = array(
                        			'path'        => $folder->getRealPath(),
                        			'application' => $client->name
                                );
                           }
                        }
                    }
                }
            }
            
            //Set the total
			$this->_total = count($langpacks);
			
			//Deal with the translatable state
			if(is_bool($state->translatable)) 
			{
			    $languages = $this->getService('com://admin/languages.model.languages')->getList()->toArray();
			  
			    if($state->translatable) {
    			    $langpacks = array_intersect_assoc($langpacks, $languages);
			    } else {
			        $langpacks = array_diff_assoc($langpacks, $languages);
			    }
			}
                
			//Apply limit and offset
            if($state->limit) {
                $langpacks = array_slice($langpacks, $state->offset, $state->limit ? $state->limit : $this->_total);
            }
                
            //Apply direction
			if(strtolower($state->direction) == 'desc') {
		        $langpacks = array_reverse($langpacks);
			}

			$rowset = $this->getService('com://admin/languages.database.rowset.langpacks');
			foreach ($langpacks as $langpack)
			{
	            $row = $rowset->getRow()->setData($langpack);
				$row->default = ($row->name == $default);

				$rowset->insert($row);
			}
			
			$this->_list = $rowset;	    
		}
		
		return parent::getList();
	}

	public function getTotal()
	{
		if (!$this->_total) {
			$this->getList();
		}

		return $this->_total;
	}
}