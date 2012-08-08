<?php
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Categories
 */
abstract class ComCategoriesControllerCategory extends ComDefaultControllerDefault
{ 
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'model' => 'com://admin/categories.model.categories'
        ));

        parent::_initialize($config);
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
		$this->_request['table']     = $this->getIdentifier()->package;
        $this->_request['access']    = JFactory::getUser()->get('aid', '0');
        $this->_request['published'] = 1;

	    return $this->_request;
	}
}