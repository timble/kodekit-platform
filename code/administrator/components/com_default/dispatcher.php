<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Dispatcher
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultDispatcher extends KDispatcherDefault implements KObjectInstantiatable
{ 
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    { 
        parent::_initialize($config);
        
        //Force the controller to the information found in the request
        if($config->request->view) {
            $config->controller = $config->request->view;
        }
    }
    
	/**
     * Force creation of a singleton
     *
     * @return KDispatcherDefault
     */
    public static function getInstance($config = array())
    {
        static $instance;
        
        if ($instance === NULL) 
        {
            //Create the singleton
            $classname = $config->identifier->classname;
            $instance = new $classname($config);
              
            //Add the factory map to allow easy access to the singleton
            KIdentifier::map('dispatcher', $config->identifier);
        }
        
        return $instance;
    }
    
    /**
     * Dispatch the controller and redirect
     * 
     * This function divert the standard behavior and will redirect if no view
     * information can be found in the request.
     * 
     * @param   string      The view to dispatch. If null, it will default to
     *                      retrieve the controller information from the request or
     *                      default to the component name if no controller info can
     *                      be found.
     *
     * @return  KDispatcherDefault
     */
    protected function _actionDispatch(KCommandContext $context)
    {
        //Redirect if no view information can be found in the request
        if(!KRequest::has('get.view')) 
        {
            $url = clone(KRequest::url());
            $url->query['option'] = 'com_'.$this->getIdentifier()->package;
            $url->query['view']   = $this->getController()->getView()->getName();
           
            JFactory::getApplication()->redirect($url);
        }
       
        return parent::_actionDispatch($context);
    }
    
    /**
     * Set the mimetype of the document and hide the menu if required
     *
     * @return  KDispatcherDefault
     */
    protected function _actionRender(KCommandContext $context)
    {
        $view = $this->getController()->getView();
        
        //Set the document mimetype
        JFactory::getDocument()->setMimeEncoding($view->mimetype);
        
        //Disabled the application menubar
        if($this->getController()->isEditable() && KInflector::isSingular($view->getName())) {
            KRequest::set('get.hidemainmenu', 1);
        } 
   
        return parent::_actionRender($context);
    }
}