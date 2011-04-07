<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Action Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KInflector
 */
abstract class KControllerForm extends KControllerModel
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->registerCallback('before.read'  , array($this, 'saveReferrer'));
		$this->registerCallback('before.browse', array($this, 'saveReferrer'));
		
		$this->registerCallback('after.read'  , array($this, 'lockRow'));
		$this->registerCallback('after.edit'  , array($this, 'unlockRow'));
		$this->registerCallback('after.cancel', array($this, 'unlockRow'));

		//Set default redirect
		$this->_redirect = KRequest::referrer();
	}
	
	/**
	 * Store the referrer in the session
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function saveReferrer(KCommandContext $context)
	{								
		$referrer = KRequest::referrer();
		
		if(isset($referrer) && KRequest::type() == 'HTTP')
		{
			$request  = KRequest::url();
			
			$request->get(KHttpUri::PART_PATH | KHttpUri::PART_QUERY);
			$referrer->get(KHttpUri::PART_PATH | KHttpUri::PART_QUERY);
			
			//Compare request url and referrer
			if($request != $referrer) {
				KRequest::set('session.com.controller.referrer', (string) $referrer);
			}
		}
	}
	
	/**
	 * Lock callback
	 * 
	 * Only lock if the context contains a row object and the view layout is 'form'. 
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function lockRow(KCommandContext $context)
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
	public function unlockRow(KCommandContext $context)
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
		$result = parent::execute($action, $context);
	    
		//Create the redirect
		$this->_redirect = KRequest::get('session.com.controller.referrer', 'url');
		return $result;
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
		$result = parent::execute($action, $context);
		
		//Create the redirect
		$this->_redirect = KRequest::url();
		
		return $result;
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
		$row = parent::_actionRead($context);

		//Create the redirect
		$this->_redirect = KRequest::get('session.com.controller.referrer', 'url');
		return $row;
	}
}