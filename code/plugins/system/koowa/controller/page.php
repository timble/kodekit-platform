<?php
/**
 * @version		$Id: page.php 587 2008-11-08 01:02:35Z mathias $
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Page Controller Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @author 		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KSecurityToken
 * @uses        KInflector
 * @uses        KHelperArray
 */
class KControllerPage extends KControllerBread
{
		
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);

		// Register extra actions
		$this->registerAction('disable', 'enable');
	}
	
	/**
	 * Get the action that is was/will be performed.
	 *
	 * @return	 string Action name
	 */
	public function getAction()
	{
		if(!isset($this->_action))
		{
			if($action = KInput::get('post.action', 'cmd'))
			{
				// action is set in the POST body
				$this->_action = $action;
			} 
			else 
			{
				// we assume either browse or read
				$view = KInput::get('get.view', 'cmd');
				$this->_action = KInflector::isPlural($view) ? 'browse' : 'read';
			}			 
		}
		
		return $this->_action;
	}
	
	/*
	 * Generic save action
	 */
	public function save()
	{
		$result = parent::edit();
		
		$view 	= KInflector::pluralize( $this->getClassName('suffix') );
		$format = KInput::get('get.format', 'cmd', null, 'html');
		
		$redirect = 'view='.$view.'&format='.$format;
		$this->setRedirect($redirect);
		
		return $result;
	}
	
	/*
	 * Generic apply action
	 */
	public function apply()
	{
		$result = parent::edit();

		$view 	= $this->getClassName('suffix');
		$format = KInput::get('get.format', 'cmd', null, 'html');
		
		$redirect = 'view='.$view.'&layout=form&id='.$row->id.'&format='.$format;
		$this->setRedirect($redirect);
		
		return $result;
	}
		
	/*
	 * Generic cancel action
	 * 
	 * @return 	this
	 */
	public function cancel()
	{
		$this->setRedirect(
			'view='.KInflector::pluralize($this->getClassName('suffix'))
			.'&format='.KInput::get('get.format', 'cmd', null, 'html')
			);
		return $this;	
	}
	
	/*
	 * Generic delete function
	 *  
	 * @throws KControllerException
	 */
	public function delete()
	{
		$result = parent::delete();

		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$view	   = KInflector::pluralize($this->getClassName('suffix'));
		$format	   = KInput::get('get.format', 'cmd', null, 'html');
				
		$this->setRedirect('view='.$view.'&format='.$format);
		
		return $result;
	}

	/*
	 * Generic enable action
	 */
	public function enable()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
	
		$cid = KInput::get('post.cid', 'array.ints', null, array());

		$enable  = $this->getAction() == 'enable' ? 1 : 0;

		if (count( $cid ) < 1) {
			throw new KControllerException(JText::sprintf( 'Select a item to %s', JText::_($this->getAction()), true ));
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
			.'&format='.KInput::get('get.format', 'cmd', null, 'html')
		);
	}
	
	/**
	 * Generic method to modify the access level of items
	 */
	public function access()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		$cid 	= KInput::get('post.cid', 'array.ints', null, array());
		$access = KInput::get('post.access', 'int');
		
		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$model     = $this->getClassName('suffix');
		$view	   = $model;

		$app   = KFactory::get('lib.joomla.application')->getName();
		$table = KFactory::get($app.'::com.'.$component.'.model.'.$model)->getTable();
		$table->update(array('access' => $access), $cid);
	
		$this->setRedirect(
			'view='.KInflector::pluralize($view)
			.'&format='.KInput::get('get.format', 'cmd', null, 'html'), 
			JText::_( 'Changed items access level')
		);
	}
	
	public function order()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		$id 	= KInput::get('post.id', 'int');
		$change = KInput::get('post.order_change', 'int');
		
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
			.'&format='.KInput::get('get.format', 'cmd', null, 'html')
		);
	}

	/**
	 * Wrapper for JRequest::get(). Override this method to modify the GET/POST data before saving
	 *
	 * @see		JRequest::get()
	 * @todo    Replace with a KInput solution
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
