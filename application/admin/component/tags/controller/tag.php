<?php
/**
 * @package     Nooku_Server
 * @subpackage  Tags
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Tag Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Tags
 */
abstract class TagsControllerTag extends Library\ControllerModel
{ 
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	//'behaviors' => array('com:activities.controller.behavior.loggable'),
            'model'     => 'com:tags.model.tags'
        ));
        
        parent::_initialize($config);
        
        //Force the toolbars
        $config->toolbars = array('menubar', 'com:tags.controller.toolbar.tag');
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
            $alias->package = 'tags';

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