<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Group Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 */
class ComGroupsControllerGroup extends ComDefaultControllerDefault
{ 
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'behaviors' => array('com://admin/activities.controller.behavior.loggable'),
        ));
        
        parent::_initialize($config);
        
        //Force the toolbars
        $config->toolbars = array('menubar', 'com://admin/groups.controller.toolbar.group');
    }
    
    protected function _actionGet(KCommandContext $context)
    {
        $view    = $this->getView();
        $package = KInflector::pluralize($this->getIdentifier()->name);
        
        if($view instanceof KViewTemplate) 
	    {     
	        //Set the layout identifier
	        $layout = clone $view->getIdentifier();
	        $layout->package  = $package;
	        $layout->name     = $view->getLayout();
	        $layout->filepath = ''; 
 
	        $view->setLayout($layout);
	        
	        //Set the template identifier
	        $template = $view->getTemplate()->getIdentifier();
	        $template->package = $package;
	    }
	     
        return parent::_actionGet($context);
    }
    
    public function setModel($model)
    {
        $model = parent::setModel($model);
        $model->package = KInflector::pluralize($this->getIdentifier()->name);
        
        return $model; 
    }
}