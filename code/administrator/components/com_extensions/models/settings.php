<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Settings Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Settings
 */

class ComExtensionsModelSettings extends KModelAbstract
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
             ->insert('name', 'cmd', null, true);        
    }
     
    public function getItem(array $options = array())
    {
        if(isset($this->getList()->{$this->getState()->name})) {
            $row = $this->getList()->{$this->getState()->name};
        } else {
            $row = $this->getList()->getRow();
        }
        
        return $row;
    }
    
    public function getList(array $options = array())
    {
        if (!isset($this->_list))
        {
            $rowset = $this->getService('com://admin/extensions.database.rowset.settings');
            
            //Insert the system configuration settings
            $rowset->insert($this->getService('com://admin/extensions.database.row.setting_system'));
                        
            //Insert the component configuration settings
            $components = $this->getService('com://admin/extensions.model.components')->enabled(1)->getList();
            foreach($components as $component)
            {
                $path  = $this->getIdentifier()->getApplication('admin');
                $path .= '/components/'.$component->name.'/config.xml';

                if(file_exists($path))
                {
                    $config = array(
                        'name' => strtolower(substr($component->name, 4)),
                        'path' => file_exists($path) ? $path : '',
                        'id'   => $component->id,
                        'data' => $component->params->toArray(),
                    );

                    $row = $this->getService('com://admin/extensions.database.row.setting_component', $config);

                    $rowset->insert($row);
                }
                    

            }
             
            $this->_list = $rowset;
        }

        return $this->_list;    
    }
}  