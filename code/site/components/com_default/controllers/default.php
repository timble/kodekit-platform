<?php
/**
 * @version     $Id: koowa.php 1296 2009-10-24 00:15:45Z johan $
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Default View Controller
.*
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */
class ComDefaultControllerDefault extends KControllerView
{
	/**
	 * Set the request information
	 * 
	 * This function translates 'limitstart' to 'offset' for compatibility with Joomla
	 *
	 * @param array	An associative array of request information
	 * @return KControllerBread
	 */
	public function setRequest(array $request = array())
	{
		if(isset($request['limitstart'])) {
			$request['offset'] = $request['limitstart'];
		}
		
		$this->_request = new KConfig($request);
		return $this;
	}
	
	/**
	 * Display the view
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function displayView(KCommandContext $context)
	{
		//Load the language file for HMVC requests who are not routed through the dispatcher
		if($this->_request->option != $this->getIdentifier()->package) {
			KFactory::get('lib.joomla.language')->load($this->_request->option); 
		}
		
		parent::displayView($context);
	}
	
	/**
	 * Browse a list of items
	 *
	 * @return void
	 */
	protected function _actionBrowse(KCommandContext $context)
	{
		if(empty($this->getModel()->getState()->limit)) {
			$this->getModel()->limit(KFactory::get('lib.joomla.application')->getCfg('list_limit'));
		}
			
		return parent::_actionBrowse($context);
	}
}