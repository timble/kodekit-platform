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
		
		// Register filter functions
		$this->registerFunctionBefore('browse' , 'loadState')
		      ->registerFunctionBefore('read'   , 'loadState');
		     
		$this->registerFunctionAfter('browse'  , 'saveState');
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
			// default action is browse (list) or read (item)
			$view 	= KRequest::get('get.view', 'cmd');
			$action = KInflector::isPlural($view) ? 'browse' : 'read';
		} 
		else
		{
			//Convert to lower case for lookup
			$action = strtolower( $action );
		}

		return parent::execute($action);
	}
	
	/**
	 * Load the model state from the session
	 *
	 * @return void
	 */
	public function loadState(KCommandContext $context)
	{
		$model   = KFactory::get($this->getModel());
		
		// Built the session identifier based on the action
		$identifier  = $model->getIdentifier();
		$identifier .= $this->_action == 'browse' ? '.list' : '.item';	
		
		echo $this->getView();
		
		$state   = KRequest::get('session.'.$identifier, 'raw', array());
		$request = KRequest::get('request', 'string');
		
		//Set the state in the model
		$model->set( KHelperArray::merge($state, $request));
	}
	
	/**
	 * Saves the model state in the session
	 *
	 * @return void
	 */
	public function saveState(KCommandContext $context)
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
	 * Browse a list of items
	 *
	 * @return void
	 */
	protected function _actionBrowse()
	{
		$layout	= KRequest::get('get.layout', 'cmd', 'default' );

		KFactory::get($this->getView())
			->setLayout($layout)
			->display();
	}

	/**
	 * Display a single item
	 *
	 * @return void
	 */
	protected function _actionRead()
	{
		$layout	= KRequest::get('get.layout', 'cmd', 'default' );

		KFactory::get($this->getView())
			->setLayout($layout)
			->display();
	}

	/**
	 * Generic edit action, saves over an existing item
	 *
	 * @return KDatabaseRow 	A row object containing the updated data
	 */
	protected function _actionEdit()
	{
		// Get the post data from the request
		$data 	= KRequest::get('post', 'string');
		
		// Get the id
		$id	 	= KRequest::get('get.id', 'int');

		// Get the row and save
		$model 	= KFactory::get($this->getModel());
		$row	= KFactory::get($model->getTable())
					->fetchRow($id)
					->setData($data)
					->save();

		return $row;
	}

	/**
	 * Generic add action, saves a new item
	 *
	 * @return KDatabaseRow 	A row object containing the new data
	 */
	protected function _actionAdd()
	{
		// Get the post data from the request
		$data = KRequest::get('post', 'string');

		// Get the row and save
		$model 	= KFactory::get($this->getModel());
		$row	= KFactory::get($model->getTable())
					->fetchRow()
					->setData($data)
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
		//Get the ids
		$ids = (array) KRequest::get('post.id', 'int');

		$model 	= KFactory::get($this->getModel());
		$rowset = KFactory::get($model->getTable())
					  ->fetchRowset($ids)
					  ->delete();

		return $rowset;
	}
}