<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Form Controller Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @author 		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KInflector
 */
class KControllerForm extends KControllerBread
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
		
		// Register filter functions
		$this->registerFilterBefore('save'   , 'filterToken');
		$this->registerFilterBefore('edit'   , 'filterToken');
		$this->registerFilterBefore('add'    , 'filterToken');
		$this->registerFilterBefore('apply'  , 'filterToken');
		$this->registerFilterBefore('cancel' , 'filterToken');
		$this->registerFilterBefore('delete' , 'filterToken');
		$this->registerFilterBefore('enable' , 'filterToken');
		$this->registerFilterBefore('disable', 'filterToken');
		$this->registerFilterBefore('access' , 'filterToken');
		$this->registerFilterBefore('order'  , 'filterToken');
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
			if($action = KRequest::get('post.action', 'cmd'))
			{
				// action is set in the POST body
				$this->_action = $action;
			} 
			else 
			{
				// we assume either browse or read
				$view = KRequest::get('get.view', 'cmd');
				$this->_action = KInflector::isPlural($view) ? 'browse' : 'read';
			}			 
		}
		
		return $this->_action;
	}
	
	/*
	 * Generic save action
	 * 
	 * @return KDatabaseRow 	A row object containing the saved data
	 */
	public function save()
	{
		$result = parent::edit();
		
		$view 	= KInflector::pluralize( $this->getClassName('suffix') );
		$format = KRequest::get('get.format', 'cmd', 'html');
		
		$redirect = 'view='.$view.'&format='.$format;
		$this->setRedirect($redirect);
		
		return $result;
	}
	
	/*
	 * Generic apply action
	 * 
	 * @return KDatabaseRow 	A row object containing the saved data
	 */
	public function apply()
	{
		$row = parent::edit();

		$view 	= $this->getClassName('suffix');
		$format = KRequest::get('get.format', 'cmd', 'html');
		
		$redirect = 'view='.$view.'&layout=form&id='.$row->id.'&format='.$format;
		$this->setRedirect($redirect);
		
		return $row;
	}
		
	/*
	 * Generic cancel action
	 * 
	 * @return 	void
	 */
	public function cancel()
	{
		$this->setRedirect(
			'view='.KInflector::pluralize($this->getClassName('suffix'))
			.'&format='.KRequest::get('get.format', 'cmd', 'html')
			);	
	}
	
	/*
	 * Generic delete function
	 *  
	 * @throws KControllerException
	 * @return void
	 */
	public function delete()
	{
		$result = parent::delete();

		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$view	   = KInflector::pluralize($this->getClassName('suffix'));
		$format	   = KRequest::get('get.format', 'cmd', 'html');
				
		$this->setRedirect('view='.$view.'&format='.$format);
		
		return $result;
	}

	/*
	 * Generic enable action
	 * 
	 * @return void
	 */
	public function enable()
	{
		$cid = (array) KRequest::get('post.cid', 'int');

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
			.'&format='.KRequest::get('get.format', 'cmd', 'html')
		);
	}
	
	/**
	 * Generic method to modify the access level of items
	 * 
	 * @return void
	 */
	public function access()
	{
		$cid 	= (array) KRequest::get('post.cid', 'int');
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
			.'&format='.KRequest::get('get.format', 'cmd', 'html'), 
			JText::_( 'Changed items access level')
		);
	}
	
	/**
	 * Generic method to modify the order level of items
	 * 
	 * @return KDatabaseRow 	A row object containing the reordered data
	 */
	public function order()
	{
		$id 	= KRequest::get('post.id', 'int');
		$change = KRequest::get('post.order_change', 'int');
		
		// Get the table object attached to the model
		$component = $this->getClassName('prefix');
		$name      = KInflector::pluralize($this->getClassName('suffix'));
		$view	   = $name;

		$app   = KFactory::get('lib.joomla.application')->getName();
		$table = KFactory::get($app.'::com.'.$component.'.table.'.$name);
		$row   = $table->fetchRow($id)
					->order($change);
		
		$this->setRedirect(
			'view='.$view
			.'&format='.KRequest::get('get.format', 'cmd', 'html')
		);
		
		return $row;
	}
	
	/**
	 * Filter the token to prevent CSRF exploirs
	 * 
	 * @return boolean	If successfull return TRUE, otherwise return false;
	 */
	public function filterToken($args)
	{		
		$req		= KRequest::get('post._token', 'md5'); 
        $token		= JUtility::getToken();
          
        return ($req === $token);
	}
}