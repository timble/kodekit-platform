<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Extensions;

use Nooku\Library;

/**
 * Settings Model
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ModelSettings extends Library\ModelAbstract
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
             ->insert('name', 'cmd', null, true);        
    }
     
    public function getRow()
    {
        if(isset($this->getRowset()->{$this->getState()->name})) {
            $row = $this->getRowset()->{$this->getState()->name};
        } else {
            $row = $this->getRowset()->getRow();
        }
        
        return $row;
    }
    
    public function getRowset()
    {
        if (!isset($this->_rowset))
        {
            $rowset = $this->getObject('com:extensions.database.rowset.settings');
            
            //Insert the system configuration settings
            $rowset->insert($this->getObject('com:extensions.database.row.setting_system'));
                        
            //Insert the component configuration settings
            $extensions = $this->getObject('com:extensions.model.extensions')->enabled(1)->getRowset();

            foreach($extensions as $extension)
            {
                $path  = Library\ClassLoader::getInstance()->getApplication('admin');
                $path .= '/component/'.substr($extension->name, 4).'/resources/config/settings.xml';

                if(file_exists($path))
                {
                    $config = array(
                        'name' => strtolower(substr($extension->name, 4)),
                        'path' => file_exists($path) ? $path : '',
                        'id'   => $extension->id,
                        'data' => $extension->params->toArray(),
                    );

                    $row = $this->getObject('com:extensions.database.row.setting_extension', $config);

                    $rowset->insert($row);
                }
            }
             
            $this->_rowset = $rowset;
        }

        return $this->_rowset;
    }
}  