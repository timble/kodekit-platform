<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */


namespace Nooku\Component\Categories;

use Nooku\Framework;

/**
 * Categories Toolbar Class
 *
 * @author  John Bell <http://nooku.assembla.com/profile/johnbell>
 * @package Nooku\Component\Categories
 */
class ControllerToolbarCategory extends \BaseControllerToolbarDefault
{
    public function onAfterControllerBrowse(Framework\Event $event)
    {    
        parent::onAfterControllerBrowse($event);
        
        $this->addSeparator();
		$this->addEnable(array('label' => 'publish'));
	    $this->addDisable(array('label' => 'unpublish'));
    }  
    
    protected function _commandNew(Framework\ControllerToolbarCommand $command)
    {
        $option = $this->getController()->getIdentifier()->package;
		$view	= Framework\Inflector::singularize($this->getIdentifier()->name);
		$table  = $this->getController()->getModel()->get('table');
		
        $command->href = 'option=com_'.$option.'&view='.$view.'&table='.$table;
    }
}