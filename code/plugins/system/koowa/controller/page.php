<?php
/**
 * @version		$Id$
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
 * @package     Koowa_Controller
 */
class KControllerPage extends KControllerAbstract
{
	/**
	 * Constructor
	 *
	 * @param array An optional associative array of configuration settings.
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		// Register extra tasks
		$this->registerTask( 'disable', 'enable');
		$this->registerTask( 'apply'  , 'save'  );
		$this->registerTask( 'add'    , 'edit'  );
	}

	public function edit()
	{
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$id  = JRequest::getInt('id', $cid[0], null);
		
		$this->setRedirect('view='.$this->getClassName('suffix').'&layout=form&id='.$id);
	}

	public function save()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		// Get the post data from the request
		$data = $this->_getRequest('post');

		// Get the table id from the session.
		$id  = JRequest::getInt('id', '', null);

		// Get the table object attached to the model
		$suffix = $this->getClassName('suffix');
		$prefix = $this->getClassName('prefix');

		$table = KFactory::get(ucfirst($prefix).'Model'.ucfirst($suffix))->getTable();
		
		if (!empty($id)) {
			$ret = $table->update($data, $id);
		} else {
			$ret = $table->insert($data);
			$id  = $table->getDBO()->insertid();
		}

        if(!$ret) {
        	JError::raiseError(500, $table->getError());
        }

		$redirect = 'format='.JRequest::getCmd('format', 'html');
		switch($this->getTask())
		{
			case 'apply' :
				$redirect = '&view='.$suffix.'&layout=form&id='.$id;
				break;

			case 'save' :
			default     :
				$redirect = '&view='.KInflector::pluralize($suffix);
		}

		$this->setRedirect($redirect);
	}
		
	/**
	 * Wrapper for JRequest::get(). Override this method to modify the GET/POST data before saving
	 *
	 * @see		JRequest::get()
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

	public function cancel()
	{
		$this->setRedirect(
			'view='.KInflector::pluralize($this->getClassName('suffix'))
			.'&format='.JRequest::getCmd('format', 'html')
			);
	}

	public function delete()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::sprintf( 'Select an item to %s', JText::_($this->getTask()), true ) );
		}

		// Get the table object attached to the model
		$suffix = $this->getClassName('suffix');
		$prefix = $this->getClassName('prefix');

		$table = KFactory::get(ucfirst($prefix).'Model'.ucfirst($suffix))->getTable();

		if(!$table->delete($cid)) {
			JError::raiseError(500, $table->getError(true));
		}

		$this->setRedirect(
			'view='.KInflector::pluralize($suffix)
			.'&format='.JRequest::getCmd('format', 'html')
		);
	}

	public function enable()
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
	
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

		$enable  = $this->getTask() == 'enable' ? 1 : 0;

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::sprintf( 'Select a item to %s', JText::_($this->getTask()), true ) );
		}

		// Get the table object attached to the model
		$suffix = $this->getClassName('suffix');
		$prefix = $this->getClassName('prefix');

		$table = KFactory::get(ucfirst($prefix).'Model'.ucfirst($suffix))->getTable();

		if(!$table->update(array('enabled' => $enable), $cid)) {
			JError::raiseError(500, $table->getError(true));
		}

		$this->setRedirect(
			'view='.KInflector::pluralize($suffix)
			.'&format='.JRequest::getCmd('format', 'html')
		);
	}
	
	protected function _setAccess($access)
	{
		KSecurityToken::check() or die('Invalid token or time-out, please try again');
		
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		KHelperArray::settype($cid, 'integer', false);
		
		// Get the table object attached to the model
		$suffix = $this->getClassName('suffix');
		$prefix = $this->getClassName('prefix');

		$table = KFactory::get(ucfirst($prefix).'Model'.ucfirst($suffix))->getTable();

		if(!$table->update(array('access' => $access), $cid)) {
			JError::raiseError(500, $table->getError(true));
		}

		$this->setRedirect(
			'view='.KInflector::pluralize($suffix)
			.'&format='.JRequest::getCmd('format', 'html'), 
			JText::_( 'Changed items access level')
		);
	}

	public function accesspublic()
	{
		$this->_setAccess(0);
	}

	public function accessregistered()
	{
		$this->_setAccess(1);
	}

	public function accessspecial()
	{
		$this->_setAccess(2);
	}
}
