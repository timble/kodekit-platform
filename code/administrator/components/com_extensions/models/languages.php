<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Languages Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 */

jimport('joomla.filesystem.folder');

class ComExtensionsModelLanguages extends KModelAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('limit'      , 'int', 0)
			->insert('offset'     , 'int', 0)
			->insert('direction'  , 'word', 'asc')
			->insert('application', 'cmd', 'site')
			->insert('default'	  , 'boolean', false, true)
			->insert('name'	      , 'com://admin/languages.filter.iso', null, true);
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
            	    $default = JComponentHelper::getParams('com_extensions')->get('language_'.$client->name, 'en-GB');
				 
				    if ($state->default) {
					    $state->language = $default;
				    }
				
				     $path  = $client->path.'/language/'.$state->name;
				    
				    //Create the row
				    $data = array(
						'path'         => $path,
						'application'  => $client->name
				    );

				    $row = KFactory::get('com://admin/extensions.database.row.language', array('data' => $data));				
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
			$state = $this->_state;

			//Get application information
			$client	= JApplicationHelper::getClientInfo($state->application, true);
			if(!empty($client)) 
			{
			    $default = JComponentHelper::getParams('com_extensions')->get('language_'.$client->name, 'en-GB');
			    
			    //Find the languages
			    $languages = array();
                $path      = $client->path.'/language';

                foreach(new DirectoryIterator($path) as $folder)
                {
                    if($folder->isDir())
                    {
                       if(file_exists($folder->getRealPath().'/'.$folder->getFilename().'.xml')) 
                       { 
                           $languages[] = array(
                        		'path'        => $folder->getRealPath(),
                        		'application' => $client->name
                            );
                       }
                    }
                }
                
                //Set the total
			    $this->_total = count($languages);
                
			    //Apply limit and offset
                if($state->limit) {
                    $languages = array_slice($languages, $state->offset, $state->limit ? $state->limit : $this->_total);
                }
                
                //Apply direction
			    if(strtolower($state->direction) == 'desc') {
				    $languages = array_reverse($languages);
			    }

			    $rowset = KFactory::get('com://admin/extensions.database.rowset.languages');
			    foreach ($languages as $language)
			    {
				    $row = $rowset->getRow()->setData($language);
				    $row->default = ($row->name == $default);

				    $rowset->insert($row);
			    }

			    $this->_list = $rowset;	    
			} 
			else  throw new KModelException('Invalid application');
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