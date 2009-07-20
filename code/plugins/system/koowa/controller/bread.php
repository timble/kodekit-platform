<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Bread Controller Class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Controller
 */
class KControllerBread extends KControllerAbstract
{
	/**
	 * Browse a list of items
	 *
	 * @return void
	 */
	protected function _actionBrowse()
	{
		$layout	= KRequest::get('get.layout', 'cmd', 'default' );

		$this->getView()
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

		$this->getView()
			->setLayout($layout)
			->display();
	}

	/*
	 * Generic edit action, saves over an existing item
	 *
	 * @return KDatabaseRow 	A row object containing the updated data
	 */
	protected function _actionEdit()
	{
		// Get the post data from the request
		$data = KRequest::get('post', 'string');

		// Get the id
		$id	 = KRequest::get('get.id', 'int');

		// Get the table object
		$app   		= $this->identifier->application;
		$package 	= $this->identifier->package;
		$name    	= KInflector::pluralize($this->identifier->name);

		$row		= $this->_getTable()
						->fetchRow($id)
						->setProperties($data)
						->save();
						
		return $row;
	}

	/*
	 * Generic add action, saves a new item
	 *
	 * @return KDatabaseRow 	A row object containing the new data
	 */
	protected function _actionAdd()
	{
		// Get the post data from the request
		$data = KRequest::get('post', 'string');

		// Get the table object
		$app   		= $this->identifier->application;
		$package 	= $this->identifier->package;
		$name    	= KInflector::pluralize($this->identifier->name);

		$row 		= $this->_getTable()
						->fetchRow()
						->setProperties($data)
						->save();

		return $row;
	}

	/*
	 * Generic delete function
	 *
	 * @return KDatabaseTableAbstract
	 */
	protected function _actionDelete()
	{
		$id = (array) KRequest::get('post.id', 'int');

		$table = $this->_getTable()
				->delete($id);
				
		return $table;
	}
	
	/**
	 * Method to get a table object
	 * 
	 * @param	array An optional associative array of configuration settings.
	 * @return	object	The table.
	 */
	protected function _getTable(array $options = array())
	{
		// Get the table object
		$app   	 = $this->identifier->application;
		$package = $this->identifier->package;
		
		// Table names are always plural
		$name    = KInflector::pluralize($this->identifier->name);
		
		$table = KFactory::get($app.'::com.'.$package.'.table.'.$name, $options);
		return $table;
	}
}