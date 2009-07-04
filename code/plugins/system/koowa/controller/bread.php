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
		$component 	= $this->getClassName('prefix');
		$suffix    	= $this->getClassName('suffix');
		$table		= KInflector::pluralize($suffix);

		$app   		= KFactory::get('lib.joomla.application')->getName();
		$table 		= KFactory::get($app.'::com.'.$component.'.table.'.$table);
		$row 		= $table->fetchRow($id)
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
		$component = $this->getClassName('prefix');
		$suffix    	= $this->getClassName('suffix');
		$table		= KInflector::pluralize($suffix);
		$view	   	= $suffix;

		$app   		= KFactory::get('lib.joomla.application')->getName();
		$table 		= KFactory::get($app.'::com.'.$component.'.table.'.$table);
		$row 		= $table->fetchRow()
					->setProperties($data)
					->save();

		return $row;
	}

	/*
	 * Generic delete function
	 *
	 * @return void
	 */
	protected function _actionDelete()
	{
		$id = (array) KRequest::get('post.id', 'int');

		// Get the table object
		$component = $this->getClassName('prefix');
		$table    	= KInflector::pluralize($this->getClassName('suffix'));

		$app   = KFactory::get('lib.joomla.application')->getName();
		$table = KFactory::get($app.'::com.'.$component.'.table.'.$table)
				->delete($id);
	}
}