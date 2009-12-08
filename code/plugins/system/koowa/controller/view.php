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
class KControllerView extends KControllerBread
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
		$this->registerActionAlias('disable', 'enable');

		// Register filter functions
		$this->registerFunctionBefore('save'   , 'checkToken')
			 ->registerFunctionBefore('edit'   , 'checkToken')
			 ->registerFunctionBefore('add'    , 'checkToken')
			 ->registerFunctionBefore('apply'  , 'checkToken')
			 ->registerFunctionBefore('cancel' , 'checkToken')
			 ->registerFunctionBefore('delete' , 'checkToken')
			 ->registerFunctionBefore('enable' , 'checkToken')
			 ->registerFunctionBefore('disable', 'checkToken')
			 ->registerFunctionBefore('access' , 'checkToken')
			 ->registerFunctionBefore('order'  , 'checkToken');
			 
		$this->registerFunctionAfter('read'   , 'saveRedirect');
		
		$this->registerFunctionAfter('save'   , 'loadRedirect')
			 ->registerFunctionAfter('cancel' , 'loadRedirect');
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
	
	/**
	 * Gets the redirect URL from the sesison and sets it in the controller
	 *
	 * @return void
	 */
	public function loadRedirect(KCommandContext $context)
	{
		if(!$redirect = KRequest::get('session.admin::com.redirect', 'url')) {
			$redirect = 'view='. KInflector::pluralize( $this->_identifier->name);
		}
			
		$this->_redirect = $redirect;	  
	}
	
	/**
	 * Get's the redirect URL from the referrer and saves in the session.
	 *
	 * @return void
	 */
	public function saveRedirect(KCommandContext $context)
	{
		$referrer = (string) KRequest::referrer();
				
		//Prevent referrer getting lost at a subsequent read action
		if($referrer != (string) KRequest::url()) {
			KRequest::set('session.admin::com.redirect', $referrer);
		}
	}
	
	/**
	 * Check the token to prevent CSRF exploits
	 *
	 * @return void
	 * @throws KControllerException
	 */
	public function checkToken(KCommandContext $context)
	{
		$req	= KRequest::get('post._token', 'md5');
        $token	= JUtility::getToken();

        if($req !== $token) 
        {
        	throw new KControllerException('Invalid token or session time-out.', KHttp::STATUS_UNAUTHORIZED);
        	return false;
        }
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
		$layout	= KRequest::get('get.layout', 'cmd', 'form' );
		
		KFactory::get($this->getView())
			->setLayout($layout)
			->display();
	}

	/*
	 * Generic save action
	 *
	 * @return KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionSave()
	{
		$row = (bool) KRequest::get('get.id', 'int') ? $this->execute('edit') : $this->execute('add');
		return $row;
	}

	/*
	 * Generic apply action
	 *
	 * @return KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionApply()
	{
		$row = $this->execute('save');
		
		$this->_redirect = 'view='.$this->_identifier->name.'&id='.$row->id;
		return $row;
	}

	/*
	 * Generic cancel action
	 *
	 * @return 	void
	 */
	protected function _actionCancel()
	{
		
	}

	/*
	 * Generic delete function
	 *
	 * @throws KControllerException
	 * @return KDatabaseTableAbstract
	 */
	protected function _actionDelete()
	{
		$table = parent::_actionDelete();

		$this->_redirect = 'view='.KInflector::pluralize($this->_identifier->name);
		return $table;
	}

	/*
	 * Generic enable action
	 *
	 * @return KDatabaseTableAbstract
	 */
	protected function _actionEnable()
	{
		$id      = (array) KRequest::get('post.id', 'int');
		$format  = KRequest::get('get.format', 'cmd', 'html');
		$enable  = $this->getAction() == 'enable' ? 1 : 0;

		if (count( $id ) < 1) {
			throw new KControllerException(JText::sprintf( 'Select a item to %s', JText::_($this->getAction()), true ));
		}

		//Update the table
		$model	= KFactory::get($this->getModel());		
		$table 	= KFactory::get($model->getTable())
					  ->update(array('enabled' => $enable), $id);

		$this->_redirect = 'view='.KInflector::pluralize($this->_identifier->name);
		return $table;
	}

	/**
	 * Generic method to modify the access level of items
	 *
	 * @return void
	 */
	protected function _actionAccess()
	{
		$id 	= (array) KRequest::get('post.id', 'int');
		$access = KRequest::get('post.access', 'int');

		//Update the table
		$model	= KFactory::get($this->getModel());		
		$table 	= KFactory::get($model->getTable())
					  ->update(array('access' => $access), $id);

		$this->_redirect = 'view='.KInflector::pluralize($this->_identifier->name);
		return $table;
	}

	/**
	 * Generic method to modify the order level of items
	 *
	 * @return KDatabaseRow 	A row object containing the reordered data
	 */
	protected function _actionOrder()
	{
		$id 	= KRequest::get('post.id', 'int');
		$change = KRequest::get('post.order_change', 'int');

		//Change the order
		$model	= KFactory::get($this->getModel());		
		$row 	= KFactory::get($model->getTable())
					->fetchRow($id)
					->order($change);

		$this->_redirect = 'view='.KInflector::pluralize($this->_identifier->name);
		return $row;
	}
}