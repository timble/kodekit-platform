<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Bread Controller Class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Controller
 */
abstract class KControllerBread extends KControllerAbstract
{
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		
		// Register command functions
		$this->registerFunctionBefore('browse' , 'loadState')
		     ->registerFunctionBefore('read'   , 'loadState');
		     
		$this->registerFunctionAfter('browse'  , array('displayView', 'saveState'))
			 ->registerFunctionAfter('read'    , 'displayView');
	}
	
	/**
	 * Execute an action by triggering a method in the derived class.
	 *
	 * @param	string		The action to perform. If null, it will default to
	 * 						either 'browse' (for list views) or 'read' (for item views)
	 * @return	mixed|false The value returned by the called method, false in error case.
	 * @throws 	KControllerException
	 */
	public function execute($action = null)
	{
		if(empty($action))
		{
			switch(KRequest::method())
			{
				case 'GET'    :
				{
					//Determine if the action is browse or read based on the view information
					$view   = KRequest::get('get.view', 'cmd');
					$action = KInflector::isPlural($view) ? 'browse' : 'read';	
				} break; 
				
				case 'POST'   :
				{
					//If an action override exists in the post request use it
					if(!$action = KRequest::get('post.action', 'cmd')) {
						$action = 'add';
					}	
				} break;
				
				case 'PUT'    : $action = 'edit'; break;
				case 'DELETE' : $action = 'delete';	break;
			}
		} 
		
		return parent::execute($action);
	}
	
	/**
	 * Load the model state from the session
	 * 
	 * This functions merges the request state information with any state information
	 * that was saved in the session and pushed the result back into the request.
	 *
	 * @return void
	 */
	public function loadState()
	{
		$model   = KFactory::get($this->getModel());
		
		// Built the session identifier based on the action
		$identifier  = $model->getIdentifier();
		$identifier .= $this->_action == 'browse' ? '.list' : '.item';	
		
		$state   = KRequest::get('session.'.$identifier, 'raw', array());
		$request = KRequest::get('request', 'string');
		
		//Set the state in the model
		KRequest::set('request',  KHelperArray::merge($state, $request));
	}
	
	/**
	 * Saves the model state in the session
	 *
	 * @return void
	 */
	public function saveState()
	{
		$model  = KFactory::get($this->getModel());
		$state  = $model->get();
		
		// Built the session identifier based on the action
		$identifier  = $model->getIdentifier();
		$identifier .= $this->_action == 'browse' ? '.list' : '.item';	
		
		//Set the state in the session
		KRequest::set('session.'.$identifier, $state);
	}
	
	/**
	 * Display the view
	 *
	 * @return void
	 */
	public function displayView()
	{
		KFactory::get($this->getView())
			->setLayout(KRequest::get('get.layout', 'cmd', 'default' ))
			->display();
	}
	
	/**
	 * Browse a list of items
	 *
	 * @return KDatabaseRowset	A rowset object containing the selected rows
	 */
	protected function _actionBrowse()
	{
		$rowset = KFactory::get($this->getModel())
					->set(KRequest::get('request', 'string'))
					->getList();
			
		return $rowset;
	}

	/**
	 * Display a single item
	 *
	 * @return KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead()
	{		
		$row = KFactory::get($this->getModel())
					->set(KRequest::get('request', 'string'))
					->getItem();
					
		return $row;
	}

	/**
	 * Generic edit action, saves over an existing item
	 *
	 * @return KDatabaseRowset 	A rowset object containing the updated rows
	 */
	protected function _actionEdit()
	{
		$rowset = KFactory::get($this->getModel())
				->set(KRequest::get('request', 'string'))
				->getList()
				->setData(KRequest::get('post', 'raw'))
				->save();

		return $rowset;
	}

	/**
	 * Generic add action, saves a new item
	 *
	 * @return KDatabaseRow 	A row object containing the new data
	 */
	protected function _actionAdd()
	{
		$row = KFactory::get($this->getModel())
				->getItem()
				->setData(KRequest::get('post', 'raw'))
				->save();

		return $row;
	}

	/**
	 * Generic delete function
	 *
	 * @return KDatabaseRowset	A rowset object containing the deleted rows
	 */
	protected function _actionDelete()
	{	
		$rowset = KFactory::get($this->getModel())
					->set(KRequest::get('request', 'string'))
					->getList()
					->delete();
			
		return $rowset;
	}
}