<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @subpackage	Command
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Editable Controller Behavior Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @subpackage	Behavior
 */
class KControllerBehaviorEditable extends KControllerBehaviorAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    { 
        parent::__construct($config);
        
        $this->registerCallback('after.save'  , array($this, 'unsetReferrer'));
		$this->registerCallback('after.cancel', array($this, 'unsetReferrer'));
	
		$this->registerCallback('after.read'  , array($this, 'lockResource'));
		$this->registerCallback('after.save'  , array($this, 'unlockResource'));
		$this->registerCallback('after.cancel', array($this, 'unlockResource'));
	
		//Make sure a referrer is set.
        $this->setReferrer();
    }
    
	/**
	 * Get the referrer
	 *
	 * @return KHttpUrl	A KHttpUrl object. 
	 */
	public function getReferrer()
	{
	    $referrer = KFactory::tmp('lib.koowa.http.url', 
	        array('url' => KRequest::get('cookie.com.controller.referrer', 'url'))
	    );
	    
	    return $referrer;
	}
          
	/**
	 * Set the referrer
	 *
	 * @return void
	 */
	public function setReferrer()
	{								   
	    if(!KRequest::has('cookie.com.controller.referrer'))
	    {
	        $referrer = KRequest::referrer();
	        $request  = KRequest::url();
			
			//Compare request url and referrer
	        if(!isset($referrer) || ((string) $referrer == (string) $request))
		    {
		        $identifier = $this->getMixer()->getIdentifier();
		          
		        $option = 'com_'.$identifier->package;
		        $view   = KInflector::pluralize($identifier->name);
		        $url    = 'index.php?option='.$option.'&view='.$view;
		    
		        $referrer = KFactory::tmp('lib.koowa.http.url',array('url' => $url));
		    }
	        
			KRequest::set('cookie.com.controller.referrer', (string) $referrer);
		}
	}
	
	/**
	 * Unset the referrer
	 *
	 * @return void
	 */
	public function unsetReferrer()
	{								  
	    KRequest::set('cookie.com.controller.referrer', null);
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
		$data   = $context->caller->execute($action, $context);
	    
		//Create the redirect
		$this->setRedirect($this->getReferrer());
		
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
		$data   = $context->caller->execute($action, $context);
		
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
		
		$this->setRedirect($url);
		
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
		$data = $context->caller->execute('read', $context);
		
		//Create the redirect
		$this->setRedirect($this->getReferrer());
	
		return $data;
	}
	
	/**
	 * Add default toolbar commands
	 * .
	 * @param	KCommandContext	A command context object
	 */
    protected function _afterRead(KCommandContext $contex)
    {
        if($this->isCommandable() && $this->hasToolbar())
        {
            $this->getToolbar()
                 ->addCommand('save')
                 ->addCommand('apply')
                 ->addCommand('cancel');
        }        
    }
}