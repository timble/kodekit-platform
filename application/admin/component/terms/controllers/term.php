<?php
/**
 * @package     Nooku_Server
 * @subpackage  Terms
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Term Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Terms
 */
abstract class TermsControllerTerm extends BaseControllerModel
{ 
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
        	//'behaviors' => array('com:activities.controller.behavior.loggable'),
            'model'     => 'com:terms.model.terms'
        ));
        
        parent::_initialize($config);
        
        //Force the toolbars
        $config->toolbars = array('menubar', 'com:terms.controller.toolbar.term');
    }
    
    protected function _actionRender(Framework\CommandContext $context)
    {
        $view = $this->getView();
        
	    //Set the layout
        if($view instanceof Framework\ViewTemplate)
	    {
	        $layout = clone $view->getIdentifier();
            $layout->name  = $view->getLayout();

            $alias = clone $layout;
            $alias->package = 'terms';

	        $this->getService()->setAlias($layout, $alias);
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