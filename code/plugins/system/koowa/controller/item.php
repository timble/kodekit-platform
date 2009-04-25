<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Item Controller Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @author 		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KSecurityToken
 * @uses        KInflector
 * @uses        KHelperArray
 */
class KControllerItem extends KControllerAbstract
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);

		// Register extra tasks
		$this->registerTask( 'disable', 'enable');
		$this->registerTask( 'apply'  , 'save'  );

	}


	/*
	 * Generic save action
	 */
	public function save()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		// Get the post data from the request
		$data = $this->_getRequest('post');

		// Get the id
		$id	 = KRequest::get('post.id', 'int');

		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$model     = $this->getClassName('suffix');
		$view	   = $model;

		$app   = KFactory::get('lib.joomla.application')->getName();
		$table = KFactory::get($app.'::com.'.$component.'.model.'.$model)->getTable();
		$row = $table->fetchRow($id)
			->setProperties($data)
			->save();

		$redirect = 'format='.KRequest::get('get.format', 'cmd', null, 'html');
		switch($this->getTask())
		{
			case 'apply' :
				$redirect = '&view='.$view.'&layout=form&id='.$row->id;
				break;

			case 'save' :
			default     :
				$redirect = '&view='.KInflector::pluralize($view);
		}

		$this->setRedirect($redirect);
	}
		
	/*
	 * Generic cancel action
	 */
	public function cancel()
	{
		$this->setRedirect(
			'view='.KInflector::pluralize($this->getClassName('suffix'))
			.'&format='.KRequest::get('get.format', 'cmd', null, 'html')
			);
	}
	
	/*
	 * Generic delete function
	 *  
	 * @throws KControllerException
	 */
	public function delete()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		$cid = KRequest::get('post.cid', 'array.ints', null, array());

		if (count( $cid ) < 1) {
			throw new KControllerException(JText::sprintf( 'Select an item to %s', JText::_($this->getTask()), true ) );
		}

		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$model     = $this->getClassName('suffix');
		$view	   = $model;

		$app   = KFactory::get('lib.joomla.application')->getName();
		$table = KFactory::get($app.'::com.'.$component.'.model.'.$model)->getTable();
		$table->delete($cid);
		
		$this->setRedirect(
			'view='.KInflector::pluralize($view)
			.'&format='.KRequest::get('get.format', 'cmd', null, 'html')
		);
	}

	/*
	 * Generic enable action
	 */
	public function enable()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
	
		$cid = KRequest::get('post.cid', 'array.ints', null, array());

		$enable  = $this->getTask() == 'enable' ? 1 : 0;

		if (count( $cid ) < 1) {
			throw new KControllerException(JText::sprintf( 'Select a item to %s', JText::_($this->getTask()), true ));
		}

		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$model     = $this->getClassName('suffix');
		$view	   = $model;
		
		$app   = KFactory::get('lib.joomla.application')->getName();
		$table = KFactory::get($app.'::com.'.$component.'.model.'.$model)->getTable();
		$table->update(array('enabled' => $enable), $cid);
	
		$this->setRedirect(
			'view='.KInflector::pluralize($view)
			.'&format='.KRequest::get('get.format', 'cmd', null, 'html')
		);
	}
	
	/**
	 * Generic method to modify the access level of items
	 */
	public function access()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		$cid 	= KRequest::get('post.cid', 'array.ints', null, array());
		$access = KRequest::get('post.access', 'int');
		
		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$model     = $this->getClassName('suffix');
		$view	   = $model;

		$app   = KFactory::get('lib.joomla.application')->getName();
		$table = KFactory::get($app.'::com.'.$component.'.model.'.$model)->getTable();
		$table->update(array('access' => $access), $cid);
	
		$this->setRedirect(
			'view='.KInflector::pluralize($view)
			.'&format='.KRequest::get('get.format', 'cmd', null, 'html'), 
			JText::_( 'Changed items access level')
		);
	}
	
	public function order()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		$id 	= KRequest::get('post.id', 'int');
		$change = KRequest::get('post.order_change', 'int');
		
		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$name      = KInflector::pluralize($this->getClassName('suffix'));
		$view	   = $name;

		$app   = KFactory::get('lib.joomla.application')->getName();
		KFactory::get($app.'::com.'.$component.'.table.'.$name)
			->fetchRow($id)
			->order($change);
		
		$this->setRedirect(
			'view='.$view
			.'&format='.KRequest::get('get.format', 'cmd', null, 'html')
		);
		
	}

	/**
	 * Wrapper for JRequest::get(). Override this method to modify the GET/POST data before saving
	 *
	 * @see		JRequest::get()
	 * @todo    Replace with a KRequest solution
	 * 
	 * @param	string	$hash	to get (POST, GET, FILES, METHOD)
	 * @param	int		$mask	Filter mask for the variable
	 * @return	mixed	Request hash
	 * @return array
	 */
	protected function _getRequest($hash = 'default', $mask = 0)
	{
		return JRequest::get($hash, $mask);
	}
}
