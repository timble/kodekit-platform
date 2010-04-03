<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Default controller dispatcher
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Dispatcher
 */

class KDispatcherDefault extends KDispatcherAbstract 
{ 
	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
	  	parent::__construct($config);
	  	
		$this->registerFunctionAfter('dispatch'   , 'forward');
	}
	
	/**
	 * Dispatch the controller and redirect
	 * 
	 * This function divert the standard behavior and will redirect if no view
	 * information can be found in the request.
	 * 
	 * @param	string		The view to dispatch. If null, it will default to
	 * 						retrieve the controller information from the request or
	 * 						default to the component name if no controller info can
	 * 						be found.
	 *
	 * @return	KDispatcherDefault
	 */
	protected function _actionDispatch($view)
	{
		//Redirect if no view information can be found in the request
		if(!KRequest::has('get.view')) 
		{
			KFactory::get('lib.koowa.application')
				->redirect('index.php?option=com_'.$this->_identifier->package.'&view='.$view);
		}
		
		return parent::_actionDispatch($view);
	}
	
	/**
	 * Forward after a post request
	 * 
	 * Either do a redirect or a execute a browse or read action in the controller
	 * depending on the request method adn type
	 *
	 * @return void
	 */
	public function _actionForward()
	{
		if(KRequest::method() == 'POST') 
		{
			if (KRequest::type() == 'HTTP') 
			{
				// Redirect if set by the controller
				if($redirect = KFactory::get($this->getController())->getRedirect())
				{
					KFactory::get('lib.koowa.application')
						->redirect($redirect['url'], $redirect['message'], $redirect['type']);
				}
			} 
			
			if(KRequest::type() == 'AJAX')  
			{
				$view = KRequest::get('get.view', 'cmd');
				KFactory::get($this->getController())->execute(KInflector::isPlural($view) ? 'browse' : 'read');		
			}
		}
	}
}