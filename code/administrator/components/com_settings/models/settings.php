<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Settings Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */

class ComSettingsModelSettings extends KModelAbstract
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
             ->insert('name', 'cmd', null, true);        
    }
     
    public function getItem()
    {
        if(isset($this->getList()->{$this->_state->name})) {
            $row = $this->getList()->{$this->_state->name};
        } else {
            $row = $this->getList()->getRow();
        }
        
        return $row;
    }
    
    public function getList()
    {
        if (!isset($this->_list))
        {
            $rowset = KFactory::get('com://admin/settings.database.rowset.settings');
            
            //Insert the system configuration settings
            $rowset->insert(KFactory::get('com://admin/settings.database.row.system'));
                        
            //Insert the component configuration settings
            $components = KFactory::get('com://admin/extensions.model.components')->enabled(1)->parent(0)->getList();
            
            foreach($components as $component)
            {
                $path   = JPATH_ADMINISTRATOR.'/components/'.$component->option.'/config.xml';
                    
                $config = array(
                	'name' => strtolower(substr($component->option, 4)),
                    'path' => file_exists($path) ? $path : '',
                    'id'   => $component->id,
                    'data' => $component->params->toArray(),
                );
                
                $row = KFactory::get('com://admin/settings.database.row.component', $config);
                
                $rowset->insert($row);
            }
             
            $this->_list = $rowset;
        }

        return $this->_list;    
    }
}  