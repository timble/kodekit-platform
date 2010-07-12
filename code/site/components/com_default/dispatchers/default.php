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
 * Default Dispatcher
.*
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */
class ComDefaultDispatcherDefault extends KDispatcherDefault
{ 
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
	 * Push the controller data into the document
	 * 
	 * This function divert the standard behavior and will push specific controller data
	 * into the document
	 *
	 * @return	KDispatcherDefault
	 */
	protected function _actionRender(KCommandContext $context)
	{
		$controller = KFactory::get($this->getController());
		$view       = KFactory::get($controller->getView());
	
		$document = KFactory::get('lib.joomla.document');
		$document->setMimeEncoding($view->mimetype);
		
		return parent::_actionRender($context);
	}
}