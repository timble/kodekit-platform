<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */


namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Categories Toolbar Class
 *
 * @author  John Bell <http://nooku.assembla.com/profile/johnbell>
 * @package Nooku\Component\Categories
 */
class ControllerToolbarCategory extends Library\ControllerToolbarModel
{
    public function onAfterControllerBrowse(Library\Event $event)
    {    
        parent::onAfterControllerBrowse($event);
        
        $this->addSeparator();
        $this->addEnable(array('label' => 'publish', 'attribs' => array('data-data' => '{published:1}')));
        $this->addDisable(array('label' => 'unpublish', 'attribs' => array('data-data' => '{published:0}')));
    }  
    
    protected function _commandNew(Library\ControllerToolbarCommand $command)
    {
        $option = $this->getController()->getIdentifier()->package;
		$view	= Library\StringInflector::singularize($this->getIdentifier()->name);
		$table  = $this->getController()->getModel()->getState()->table;
		
        $command->href = 'option=com_'.$option.'&view='.$view.'&table='.$table;
    }
}