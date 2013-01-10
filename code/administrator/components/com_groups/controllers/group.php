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
class ComGroupsControllerGroup extends ComDefaultControllerModel
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
    
    protected function _actionRender(KCommandContext $context)
    {
        $view = $this->getView();

        //Set the layout
        if($view instanceof KViewTemplate)
        {
            $layout = clone $view->getIdentifier();
            $layout->name  = $view->getLayout();

            $alias = clone $layout;
            $alias->package = 'groups';

            $this->getService()->setAlias($layout, $alias);
        }

        return parent::_actionRender($context);
    }
    
    public function setModel($model)
    {
        $model = parent::setModel($model);
        $model->package = KInflector::pluralize($this->getIdentifier()->name);
        
        return $model; 
    }
}