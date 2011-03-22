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
class ComDefaultDispatcher extends KDispatcherDefault
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
        $config->append(array(
            'request_persistent' => true
        ));

        parent::_initialize($config);
    }
    
	/**
	 * Check the token to prevent CSRF exploits
	 *
	 * @return  void|false Returns false if the authorization failed
	 * @throws 	KDispatcherException
	 */
	public function _actionAuthorize(KCommandContext $context)
	{
        $result = parent::_actionAuthorize($context);
        
        if(KRequest::method() != KHttpRequest::GET) 
        {
            if( KRequest::token() !== JUtility::getToken())
            {
        	    throw new KDispatcherException('Invalid token or session time-out.', KHttpResponse::FORBIDDEN);
        	    $result = false;
            }
        }
        
        return $result;
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
            $view = count($context->data) ? $context->data : $this->_controller_default;
            
            $url = clone(KRequest::url());
            $url->query['view'] = $view;
            
            KFactory::get('lib.koowa.application')->redirect($url);
        }
        
        return parent::_actionDispatch($context);
    }
    
    /**
     * Push the controller data into the document
     * 
     * This function divert the standard behavior and will push specific controller data
     * into the document
     *
     * @return  KDispatcherDefault
     */
    protected function _actionRender(KCommandContext $context)
    {
        $controller = KFactory::get($this->getController());
        $view       = $controller->getView();
    
        $document = KFactory::get('lib.joomla.document');
        $document->setMimeEncoding($view->mimetype);
        
        if($view instanceof ComDefaultViewHtml)
        {
            $document->setBuffer($view->getToolbar()->render(), 'modules', 'toolbar');
            $document->setBuffer($view->getToolbar()->renderTitle(), 'modules', 'title');
            
            if(KInflector::isSingular($view->getName()) && !KRequest::has('get.hidemainmenu')) {
                KRequest::set('get.hidemainmenu', 1);
            }
            
            if(isset($view->views)) 
            {
                foreach($view->views as $name => $title)
                {
                    $active    = ($name == strtolower($view->getName()));
                    $component = $this->_identifier->package;
            
                    JSubMenuHelper::addEntry(JText::_($title), 'index.php?option=com_'.$component.'&view='.$name, $active );
                }
            }       
        }
        
        return parent::_actionRender($context);
    }
}