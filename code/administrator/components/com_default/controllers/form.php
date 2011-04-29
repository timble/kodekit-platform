<?php
/**
 * @version     $Id: default.php 3027 2011-03-29 19:20:06Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Form Controller
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerForm extends KControllerResource
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->registerCallback('before.read' , array($this, 'setReferrer'));
        $this->registerCallback('after.save'  , array($this, 'unsetReferrer'));
		$this->registerCallback('after.cancel', array($this, 'unsetReferrer'));
	
		$this->registerCallback('after.read'  , array($this, 'lockResource'));
		$this->registerCallback('after.save'  , array($this, 'unlockResource'));
		$this->registerCallback('after.cancel', array($this, 'unlockResource'));
		
        //Set default redirect
		$this->_redirect = KRequest::referrer();
    }
          
	/**
	 * Set the referrer in the session
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function setReferrer(KCommandContext $context)
	{								   
	    if(!$referrer = KRequest::get('session.com.controller.referrer', 'url'))
	    {
	        $referrer = KRequest::referrer();
	               
	       //If we don't have a referrer set the plural view
		    if(!isset($referrer))
		    {
		        $option = 'com_'.$this->_identifier->package;
		        $view   = KInflector::pluralize($this->_identifier->name);
		        $url    = 'index.php?option='.$option.'&view='.$view;
		    
		        $referrer = KFactory::tmp('lib.koowa.http.uri',array('uri' => $url));
		    }
		    
			KRequest::set('session.com.controller.referrer', (string) $referrer);
		}
	}
	
	/**
	 * Unset the referrer from the session
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function unsetReferrer(KCommandContext $context)
	{								  
	    KRequest::set('session.com.controller.referrer', null);
	}
	
	/**
	 * Lock callback
	 * 
	 * Only lock if the context contains a row object and the view layout is 'form'. 
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function lockResource(KCommandContext $context)
	{								
       if($context->result instanceof KDatabaseRowInterface) 
       {
	        $view = $this->getView();
	    
	        if($view instanceof KViewTemplate)
	        {
                if($view->getLayout() == 'form' && $context->result->isLockable()) {
		            $context->result->lock();
		        }
            }
	    }
	}
	
	/**
	 * Unlock callback
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function unlockResource(KCommandContext $context)
	{								  
	    if($context->result->isLockable()) {
			$context->result->unlock();
		}
	}
	   
	/**
	 * Save action
	 * 
	 * This function wraps around the edit or add action. If the model state is
	 * unique a edit action will be executed, if not unique an add action will be
	 * executed.
	 * 
	 * This function also sets the redirect to the referrer.
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionSave(KCommandContext $context)
	{
		$action = $this->getModel()->getState()->isUnique() ? 'edit' : 'add';
		$data   = parent::execute($action, $context);
	    
		//Create the redirect
		$this->_redirect = KRequest::get('session.com.controller.referrer', 'url');
	
		return $data;
	}

	/**
	 * Apply action
	 * 
	 * This function wraps around the edit or add action. If the model state is
	 * unique a edit action will be executed, if not unique an add action will be
	 * executed.
	 * 
	 * This function also sets the redirect to the current url
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionApply(KCommandContext $context)
	{
		$action = $this->getModel()->getState()->isUnique() ? 'edit' : 'add';
		$data   = parent::execute($action, $context);
		
		//Create the redirect
		$url  = clone KRequest::url();
	
		if($this->getModel()->getState()->isUnique())
		{
	        $url    = clone KRequest::url();
	        $states = $this->getModel()->getState()->getData(true);
		
		    foreach($states as $key => $value) {
		        $url->query[$key] = $data->get($key);
		    }
		}
		else $url->query[$data->getIdentityColumn()] = $data->get($data->getIdentityColumn());
		
		$this->_redirect = $url;
		
		return $data;
	}
	
	/**
	 * Cancel action
	 * 
	 * This function will unlock the row(s) and set the redirect to the referrer
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow	A row object containing the data of the cancelled object
	 */
	protected function _actionCancel(KCommandContext $context)
	{
		//Don't pass through the command chain
		$data = parent::_actionRead($context);

		//Create the redirect
		$this->_redirect = KRequest::get('session.com.controller.referrer', 'url');
		
		return $data;
	}
}