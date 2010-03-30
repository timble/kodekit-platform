<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Action Controller Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @author 		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KInflector
 */
abstract class KControllerAction extends KControllerBread
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Register actions aliasses
		$this->registerActionAlias('disable', 'enable');
		$this->registerActionAlias('unlock' , 'lock');
	 
		$this->registerFunctionBefore('read', 'saveReferrer');
		
		//Set default redirect
		$this->_redirect = KRequest::url();
	}
	
	/**
	 * Check the token to prevent CSRF exploits before executing the action
	 *
	 * @param	string		The action to perform. If null, it will default to
	 * 						either 'browse' (for list views) or 'read' (for item views)
	 * @return	mixed|false The value returned by the called method, false in error case.
	 * @throws 	KControllerException
	 */
	public function execute($action = null, $data = null)
	{
		if(KRequest::method() == 'POST') 
		{
			$req	= KRequest::get('request._token', 'md5');
       	 	$token	= JUtility::getToken();

        	if($req !== $token) 
        	{
        		throw new KControllerException('Invalid token or session time-out.', KHttp::STATUS_UNAUTHORIZED);
        		return false;
        	}
		}
		
		return parent::execute($action, $data);
	}
	
	/**
	 * Store the referrer in the session
	 *
	 * @return void
	 */
	public function saveReferrer()
	{
		if(KRequest::type() == 'HTTP') 
		{	
			$referrer = KRequest::referrer();
			$request  = KRequest::url();
			
			//Prevent referrer getting lost at a subsequent read action
			foreach(array('option', 'view') as $var) 
			{
				if(isset($referrer->query[$var]) && isset($request->query[$var])) 
				{
					if($referrer->query[$var] != $request->query[$var]) {
						 KRequest::set('session.com.dispatcher.referrer', (string) $referrer);
						 break;
					}
				}
			}
		}
	}
	
	/**
	 * Display a single item
	 *
	 * @return KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead()
	{		
		$row = parent::_actionRead();
		
		if(isset($row)) 
		{
			//Lock the row 
			if(KRequest::get('get.layout', 'cmd') == 'form' && $row->isLockable()) {
				$row->lock();
			}
		}
			
		return $row;
	}		
			
	/*
	 * Generic save action
	 *
	 *	@param	mixed 	Either a scalar, an associative array, an object 
	 * 					or a KDatabaseRow
	 * @return KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionSave($data)
	{
		if(KFactory::get($this->getModel())->getState()->isUnique()) 
		{
			//Edit returns a rowset
			$rowset = $this->execute('edit', $data);
			
			// Get the row based on the identity key
			$row = $rowset->find(KFactory::get($this->getModel())->getState()->id);
			
			//Unlock the row 
			if($row->isLockable()) {
				$row->unlock();
			}
			
		} else $row = $this->execute('add', $data);
		
		$this->_redirect = KRequest::get('session.com.dispatcher.referrer', 'url');
		return $row;
	}
	
	/*
	 * Generic apply action
	 *
	 *	@param	mixed 	Either a scalar, an associative array, an object 
	 * 					or a KDatabaseRow
	 * @return 	KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionApply($data)
	{
		if(KFactory::get($this->getModel())->getState()->isUnique()) 
		{
			//Edit returns a rowset
			$rowset = $this->execute('edit', $data);
			
			// Get the row based on the identity key
			$row = $rowset->find(KFactory::get($this->getModel())->getState()->id);
			
			//Unlock the row 
			if($row->isLockable()) {
				$row->unlock();
			}
		}
		else $row = $this->execute('add', $data);
		
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
		//Don't pass through the command chain
		$row = parent::_actionRead();
					
		if($row->isLockable()) {
			$row->unlock();
		}
		
		$this->_redirect = KRequest::get('session.com.dispatcher.referrer', 'url');
		return $row;
	}
	

	/*
	 * Generic method to modify the enabled state of and item(s)
	 *
	 * @return KDatabaseRowset
	 */
	protected function _actionEnable()
	{
		$data = array('enabled' => $this->getAction() == 'enable' ? '1' : '0');
		$rowset = $this->execute('edit', $data);
		
		return $rowset;
	}

	/**
	 * Generic method to modify the access level of an item(s)
	 *
	 * @return KDatabaseRowset
	 */
	protected function _actionAccess()
	{
		$data = array('access' => KRequest::get('post.access', 'int'));
		$rowset = $this->execute('edit', $data);

		return $rowset;
	}
	
	/**
	 * Generic method to modify the order level of an item(s)
	 *
	 * @return KDatabaseRow 	A row object containing the reordered data
	 */
	/*protected function _actionOrder()
	{
		$model	= KFactory::get($this->getModel());		
		$row 	= KFactory::get($model->getTable())
					->fetchRow(KRequest::get('post.id', 'int'))
					->order(KRequest::get('post.order_change', 'int'));
		
		return $row;
	}*/
}