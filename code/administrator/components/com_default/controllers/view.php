<?php
/**
 * @version     $Id: koowa.php 1296 2009-10-24 00:15:45Z johan $
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
class ComDefaultControllerView extends KControllerView
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		//Register command functions
		$this->registerFunctionAfter(array('save', 'delete'), 'setMessage');
	}
	
 	/**
	 * Filter that creates a redirect message based on the action
	 *
	 * @return void
	 */
	public function setMessage()
	{
		$count  = count((array) KRequest::get('post.id', 'int', 1));
		$action = KRequest::get('post.action', 'cmd');
		$name	= $this->getIdentifier()->name;
			
		if($count > 1) {
			$this->_redirect_message = JText::sprintf('%s ' . strtolower(KInflector::pluralize($name)) . ' ' . $action.'d', $count);
		} else {
			$this->_redirect_message = JText::_(ucfirst(KInflector::singularize($name)) . ' ' . $action.'d');
		}
	}
	
	/**
	 * Browse a list of items
	 *
	 * @return KDatabaseRowset	A rowset object containing the selected rows
	 */
	protected function _actionBrowse()
	{
		KFactory::get($this->getModel())
			->set('limit', KFactory::get('lib.joomla.application')->getCfg('list_limit'));
			
		return parent::_actionBrowse();
	}
	
	/**
	 * Display a single item
	 * 
	 * This functions implements an extra check to hide the main menu is the view name
	 * is singular (item views)
	 *
	 *  @return KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead()
	{
		if(KInflector::isSingular(KFactory::get($this->getView())->getName())){
			KRequest::set('get.hidemainmenu', 1);
		}
		
		//Force the default layout to form for read actions
		if(!KRequest::has('get.layout')) {
			KRequest::set('get.layout', 'form');
		}
		
		return parent::_actionRead();
	}
}