<?php
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Category Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Categories
 */
abstract class CategoriesControllerCategory extends Library\ControllerModel
{ 
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	'behaviors' => array(
                'com:activities.controller.behavior.loggable',
                'com:attachments.controller.behavior.attachable',
            ),
            'model'     => 'com:categories.model.categories'
        ));
        
        parent::_initialize($config);
        
        //Force the toolbars
        $config->toolbars = array('menubar', 'com:categories.controller.toolbar.category');
    }
    
    protected function _actionRender(Library\CommandContext $context)
    {
        $view = $this->getView();
        
	    //Set the layout
        if($view instanceof Library\ViewTemplate)
	    {
	        $layout = clone $view->getIdentifier();
            $layout->name  = $view->getLayout();

            $alias = clone $layout;
            $alias->package = 'categories';

	        $this->getObject('manager')->registerAlias($layout, $alias);
	    }
	        
        return parent::_actionRender($context);
    }
    
    public function getRequest()
	{
		$request = parent::getRequest();

        $request->query->table  = $this->getIdentifier()->package;

	    return $request;
	}
}