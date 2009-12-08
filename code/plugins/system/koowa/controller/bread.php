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
	 * Load the model state from the session
	 *
	 * @return void
	 */
	public function loadState(KCommandContext $context)
	{
		$model   = KFactory::get($this->getModel());
		$state   = KRequest::get('session.'.$model->getIdentifier(), 'raw', array());
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
					
		//Set the state in the session
		KRequest::set('session.'.$model->getIdentifier(), $state);
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