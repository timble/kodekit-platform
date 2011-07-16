<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Templates Model Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions   
 */
class ComExtensionsModelTemplates extends KModelAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
            ->insert('limit'      , 'int')
            ->insert('offset'     , 'int')
            ->insert('sort'       , 'cmd')
            ->insert('direction'  , 'word', 'asc')
            ->insert('application', 'cmd', 'site')
            ->insert('default'    , 'boolean', false, true)
            ->insert('name'       , 'cmd', null, true);        
    }

    /**
     * Method to get a item
     *
     * @return KDatabaseRowInterface
     */
    public function getItem()
    {
        if(!isset($this->_item))
        {
            $state = $this->_state;
            
            //Get application information
			$client	= JApplicationHelper::getClientInfo($state->application, true);
            if (!empty($client)) 
            {
                //Get the path
                $default = JComponentHelper::getParams('com_extensions')->get('template_'.$client->name, 'site');
             
                if ($state->default) {
			        $state->name = $default;
				}
			
                $path  = $client->path.'/templates/'.$state->name;

                $data = array(
                	'path'        => $path,
                	'application' => $client->name
                );

                $row = KFactory::tmp('admin::com.extensions.database.row.template', array('data' => $data));
                $row->default = ($row->name == $default);
                
                $this->_item = $row;
            }
            else throw new KModelException('Invalid application');
        }
        
        return $this->_item;
    }

    /**
     * Get a list of items
     *
     * @return KDatabaseRowsetInterface
     */
    public function getList()
    { 
        if(!isset($this->_list))
        {
            $state = $this->_state;
            
            //Get application information
			$client	= JApplicationHelper::getClientInfo($state->application, true);
			if(!empty($client)) 
			{
                $default = JComponentHelper::getParams('com_extensions')->get('template_'.$client->name, 'site');
			    
			    //Find the templates
			    $templates = array();
                $path      = $client->path.'/templates';

                foreach(new DirectoryIterator($path) as $folder)
                {
                    if($folder->isDir())
                    {
                        if(file_exists($folder->getRealPath().'/templateDetails.xml')) 
                        { 
                            $templates[] = array(
                       			'path'        => $folder->getRealPath(),
                        		'application' => $client->name
                            );
                        }
                    }
                }
                
                //Set the total
			    $this->_total = count($templates);

                //Apply limit and offset
                if($this->_state->limit) {
                    $templates = array_slice($template, $state->offset, $state->limit ? $state->limit : $this->_total);
                }
                
			     //Apply direction
			    if(strtolower($state->direction) == 'desc') {
				    $templates = array_reverse($templates);
			    }
                
                //Create the rowset
                $rowset = KFactory::tmp('admin::com.extensions.database.rowset.templates');
			    foreach ($templates as $template)
			    {
			        $row = $rowset->getRow()->setData($template);
			        $row->default = ($row->name == $default);

				    $rowset->insert($row);
			     }

			     $this->_list = $rowset;
			}
			else throw new KModelException('Invalid application');
        }

        return $this->_list;
    }

    public function getTotal()
	{
		if (!$this->_total) {
			$this->getList();
		}

		return $this->_total;
	}
    
    public function getColumn($column)
	{
		return $this->getList();
	}
}