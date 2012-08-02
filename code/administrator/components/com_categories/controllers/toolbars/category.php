<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Categories Toolbar Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @package     Nooku_Server
 * @subpackage  Categories   
 */
class ComCategoriesControllerToolbarCategory extends ComDefaultControllerToolbarDefault
{
    public function onAfterControllerBrowse(KEvent $event)
    {    
        parent::onAfterControllerBrowse($event);
        
        $this->addSeparator()     
			 ->addEnable(array('label' => 'publish'))
			 ->addDisable(array('label' => 'unpublish'));
    }  
    
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $option = $this->getController()->getIdentifier()->package;
		$view	= KInflector::singularize($this->getIdentifier()->name);
		$table  = $this->getController()->getModel()->get('table');
		
        $command->attribs->href = JRoute::_('index.php?option=com_'.$option.'&view='.$view.'&table='.$table );
    }
}