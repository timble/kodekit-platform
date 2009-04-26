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
 * List Controller Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @author 		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KSecurityToken
 * @uses        KInflector
 * @uses        KHelperArray
 */
class KControllerList extends KControllerAbstract
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
		$this->registerTask( 'add', 'edit'  );
	}


	/*
	 * Generic edit action
	 */
	public function edit()
	{
		$cid = (array) KRequest::get('get.cid', 'int');
		$id	 = KRequest::get('get.id', 'int', null, $cid[0]);
		 
		$this->setRedirect('view='.KInflector::singularize($this->getClassName('suffix')).'&layout=form&id='.$id);
	}
	
	/*
	 * Generic delete function
	 *  
	 * @throws KControllerException
	 */
	public function delete()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		$cid = (array) KRequest::get('post.cid', 'int');

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
	
		$cid = (array) KRequest::get('post.cid', 'int');

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
			.'&format='.KRequest::get('get.format', 'cmd', null, 'html'), 
			JText::_( 'Changed items access level')
		);
	}
}