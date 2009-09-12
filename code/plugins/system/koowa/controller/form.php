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
		$this->registerActionAlias('disable', 'enable');

		// Register filter functions
		$this->registerFilterBefore('save'   , 'filterToken')
			 ->registerFilterBefore('edit'   , 'filterToken')
			 ->registerFilterBefore('add'    , 'filterToken')
			 ->registerFilterBefore('apply'  , 'filterToken')
			 ->registerFilterBefore('cancel' , 'filterToken')
			 ->registerFilterBefore('delete' , 'filterToken')
			 ->registerFilterBefore('enable' , 'filterToken')
			 ->registerFilterBefore('disable', 'filterToken')
			 ->registerFilterBefore('access' , 'filterToken')
			 ->registerFilterBefore('order'  , 'filterToken');
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
	 * Filter the token to prevent CSRF exploits
	 *
	 * @return boolean	If successfull return TRUE, otherwise return false;
	 * @throws KControllerException
	 */
	public function filterToken(ArrayObject $args)
	{
		$req	= KRequest::get('post._token', 'md5');
        $token	= JUtility::getToken();

        if($req !== $token) {
        	throw new KControllerException('Invalid token or session time-out.', KHttp::STATUS_UNAUTHORIZED);
        }
        return true;
	}
	
	/**
	 * Browse a list of items
	 *
	 * @return void
	 */
	protected function _actionBrowse()
	{
		$layout	= KRequest::get('get.layout', 'cmd', 'form' );

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
		$layout	= KRequest::get('get.layout', 'cmd', 'form' );

		$this->getView()
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
		$format = KRequest::get('get.format', 'cmd', 'html');
		$row    = KRequest::get('get.id', 'boolean') ? $this->execute('edit') : $this->execute('add');

		$this->setRedirect('view='. KInflector::pluralize( $this->_identifier->name).'&format='.$format);
		return $row;
	}

	/*
	 * Generic apply action
	 *
	 * @return KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionApply()
	{
		$format = KRequest::get('get.format', 'cmd', 'html');
		$row    = KRequest::get('get.id', 'boolean') ? $this->execute('edit') : $this->execute('add');
	
		$this->setRedirect('view='.$this->_identifier->name.'&layout=form&id='.$row->id.'&format='.$format);
		return $row;
	}

	/*
	 * Generic cancel action
	 *
	 * @return 	void
	 */
	protected function _actionCancel()
	{
		$format	= KRequest::get('get.format', 'cmd', 'html');
		
		$this->setRedirect('view='.KInflector::pluralize($this->_identifier->name).'&format='.$format);
	}

	/*
	 * Generic delete function
	 *
	 * @throws KControllerException
	 * @return KDatabaseTableAbstract
	 */
	protected function _actionDelete()
	{
		$format	  = KRequest::get('get.format', 'cmd', 'html');
		
		$table = parent::_actionDelete();

		// Redirect
		$this->setRedirect('view='.KInflector::pluralize($this->_identifier->name).'&format='.$format);
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
		$table = $this->getModel()
					  ->getTable()
					  ->update(array('enabled' => $enable), $id);

		$this->setRedirect('view='.KInflector::pluralize($this->_identifier->name).'&format='.$format);

		return $this->table;
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
		$fomat  = KRequest::get('get.format', 'cmd', 'html');

		//Update the table
		$table = $this->getModel()
					  ->getTable()
					  ->update(array('access' => $access), $id);

		$this->setRedirect('view='.KInflector::pluralize($this->_identifier->name).'&format='.$format,
			JText::_( 'Changed items access level')
		);
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
		$format = KRequest::get('get.format', 'cmd', 'html');

		//Change the order
		$row = $this->getModel()
					->getTable()
					->fetchRow($id)
					->order($change);

		$this->setRedirect('view='.KInflector::pluralize($this->_identifier->name).'&format='.$format);
		return $row;
	}
}