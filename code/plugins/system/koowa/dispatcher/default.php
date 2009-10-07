<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
	 * Dispatch the controller and redirect
	 * 
	 * This function divert the standard behavior and will redirect if now view
	 * information can be found in the request.
	 * 
	 * @param	string		The view to dispatch. If null, it will default to
	 * 						retrieve the controller information from the request or
	 * 						default to the component name if no controller info can
	 * 						be found.
	 *
	 * @return	KDispatcherDefault
	 */
	public function dispatch($view)
	{
		//Redirect if no view information can be found in the request
		if(!KRequest::has('get.view')) 
		{
			KFactory::get('lib.joomla.application')
				->redirect('index.php?option=com_'.$this->_identifier->package.'&view='.$view);
		}
	
		return parent::dispatch($view);
	}
}