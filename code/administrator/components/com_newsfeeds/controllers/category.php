<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */
class ComNewsfeedsControllerCategory extends ComDefaultControllerDefault
{ 
    protected function _actionGet(KCommandContext $context)
    {
        $view = $this->getView();
        
	    //Set the layout
        if($view instanceof KViewTemplate) 
	    {
	        $layout = clone $view->getIdentifier();
	        $layout->package  = 'categories';
	        $layout->name     = $view->getLayout();
	        
	        //Force re-creation of the filepath to load the category templates
	        $layout->filepath = ''; 
 
	        $view->setLayout($layout);
	    }
	    
	    //Set the toolbar
	    if($this->isCommandable()) {
	        $this->setToolbar('admin::com.categories.controller.toolbar.'.$view->getName());
	    }
	    
        return parent::_actionGet($context);
    }
    
    public function setModel($model)
    {
        $model = parent::setModel($model);
        $model->package = 'categories';
        
        return $model; 
    }
     
	public function getRequest()
	{
		$this->_request['section'] = 'com_newsfeeds';
	    return $this->_request;
	}
}