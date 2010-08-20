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
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		//Register command callbacks
		$this->registerCallback(array('after.save', 'after.delete'), array($this, 'setMessage'));
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
		$name	= $this->_identifier->name;
		$suffix = ($action == 'add' || $action == 'edit') ? 'ed' : 'd'; 
			
		if($count > 1) {
			$this->_redirect_message = JText::sprintf('%s ' . strtolower(KInflector::pluralize($name)) . ' ' . $action.$suffix, $count);
		} else {
			$this->_redirect_message = JText::_(ucfirst(KInflector::singularize($name)) . ' ' . $action.$suffix);
		}
	}
	
	/**
	 * Browse a list of items
	 * 
	 * This function set the default list limit if the limit state is 0
	 *
	 * @return KDatabaseRowset	A rowset object containing the selected rows
	 */
	protected function _actionBrowse()
	{
		$model = KFactory::get($this->getModel());
		if($model->getState()->limit === 0) {
			$model->set('limit', KFactory::get('lib.joomla.application')->getCfg('list_limit'));
		}
				
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
		//Force the default layout to form for read actions
		if(!isset($this->_request->layout)) {
			$this->_request->layout = 'form';
		}
		
		//Perform the read action
		$row = parent::_actionRead();
		
		//Add the notice if the row is locked
		if(isset($row)) 
		{
			if($this->_request->layout == 'form' && $row->isLockable() && $row->locked()) {
				KFactory::get('lib.koowa.application')->enqueueMessage($row->lockMessage(), 'notice');
			}
		}
	
		return $row;
	}
}