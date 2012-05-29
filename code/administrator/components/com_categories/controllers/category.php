<?php
/**
 * @version		$Id: category.php 2034 2011-06-26 17:08:29Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
abstract class ComCategoriesControllerCategory extends ComDefaultControllerDefault
{ 
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'behaviors' => array('com://admin/activities.controller.behavior.loggable'),
            'model'     => 'com://admin/categories.model.categories'
        ));
        
        parent::_initialize($config);
        
        //Force the toolbars
        $config->toolbars = array('menubar', 'com://admin/categories.controller.toolbar.category');
    }
    
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
	        
        return parent::_actionGet($context);
    }
    
    public function getRequest()
	{
		$this->_request['section'] = 'com_'.$this->getIdentifier()->package;
	    return $this->_request;
	}
}