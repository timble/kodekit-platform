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
        /* 
         * Disable model persistency on non-HTTP requests, e.g. AJAX, and requests containing 
         * the tmpl variable set to component, e.g. requests using modal boxes. This avoids 
         * changing the model state session variable of the requested model, which is often 
         * undesirable under these circumstances. 
         */
        
        $persistent = (KRequest::type() == 'HTTP' && KRequest::get('get.tmpl','cmd') != 'component');
        
        $config->append(array(
            'request_persistent' => $persistent
        ));
        
        parent::_initialize($config);
        
        //Force the controller to the information found in the request
        if($config->request->view) {
            $config->controller = $config->request->view;
        }
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
            $url->query['view'] = $this->getController()->getView()->getName();
           
            KFactory::get('lib.joomla.application')->redirect($url);
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
        $view  = $this->getController()->getView();
    
        $document = KFactory::get('lib.joomla.document');
        $document->setMimeEncoding($view->mimetype);
        
        if($view instanceof ComDefaultViewHtml)
        {
            $name       = KInflector::isPlural($view->getName()) ? 'list' : 'item';
            $identifier = 'admin::com.default.view.'.$name;
            $config     = array('toolbar' => $view->getToolbar());
            
            //Render the toolbar
            $toolbar = $view->getTemplate()
                           ->loadIdentifier($identifier.'.toolbar', $config)
                           ->render();
                           
            $document->setBuffer($toolbar, 'modules', 'toolbar');
            
            //Render the title
            $title   = $view->getTemplate()
                            ->loadIdentifier($identifier.'.toolbar_title' , $config)
                            ->render();
           
            $document->setBuffer($title, 'modules', 'title'  );
            
            //Render the submenu
            if(isset($view->views)) 
            {
                foreach($view->views as $name => $title)
                {
                    $active    = ($name == strtolower($view->getName()));
                    $component = $this->_identifier->package;
            
                    JSubMenuHelper::addEntry(JText::_($title), 'index.php?option=com_'.$component.'&view='.$name, $active );
                }
            }
            
            if(KInflector::isSingular($view->getName()) && !KRequest::has('get.hidemainmenu')) {
                KRequest::set('get.hidemainmenu', 1);
            }      
        }
        
        return parent::_actionRender($context);
    }
}