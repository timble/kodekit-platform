<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
abstract class KControllerBread extends KControllerAbstract
{
	/**
	 * Model object or identifier (APP::com.COMPONENT.model.NAME)
	 *
	 * @var	string|object
	 */
	protected $_model;
	
	/**
	 * View object or identifier (APP::com.COMPONENT.view.NAME)
	 *
	 * @var	string|object
	 */
	protected $_view;
	
	/**
	 * The request information
	 *
	 * @vara array
	 */
	protected $_request = null;
	
	/**
	 * Constructor
	 * 
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		    	 
		$this->registerFunctionAfter('browse'  , 'displayView')
			 ->registerFunctionAfter('read'    , 'displayView');
	 
		$this->registerFunctionAfter('browse'  , 'saveModelState');
	}
	
 	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
        	'model'			=> null,
        	'view'			=> null,
        ));
        
        parent::_initialize($config);
    }
	
	/**
	 * Execute an action by triggering a method in the derived class.
	 *
	 * @param	string	The action to perform. If null, the action will be determined base 
	 * 					on the request method.
	 * @param	mixed 	Either a scalar, an associative array, an object or a KDatabaseRow
	 * @return	mixed|false The value returned by the called method, false in error case.
	 * @throws 	KControllerException
	 */
	public function execute($action = null, $data = null)
	{
		//Get the action 
		if(!isset($action)) { 
			$action = $this->getAction(); 
		}
		
		///Get the data
		if(!isset($data)) {
			$data = KRequest::get('post', 'raw');
		}
		
		// Get the request and set it in the model
		KFactory::get($this->getModel())->set($this->getRequest());
	
		return parent::execute($action, $data);
	}
	
	/**
	 * Get the action that is was/will be performed.
	 *
	 * @return	 string Action name
	 */
	public function getAction()
	{
		if(!$this->_action)
		{
			switch(KRequest::method())
			{
				case 'GET'    :
				{
					//Determine if the action is browse or read based on the view information
					$view   = KRequest::get('get.view', 'cmd');
					$action = KInflector::isPlural($view) ? 'browse' : 'read';	
				} break; 
				
				case 'POST'   :
				{
					//If an action override exists in the post request use it
					if(!$action = KRequest::get('post.action', 'cmd')) {
						$action = 'add';
					}	
				} break;
				
				case 'PUT'    : $action = 'edit'  ; break;
				case 'DELETE' : $action = 'delete';	break;
			}
			
			$this->_action = $action;
		}
		
		return $this->_action;
	}
	
	/**
	 * Load the model state from the request
	 * 
	 * This functions merges the request information with any model state information
	 * that was saved in the session and returns the result.
	 *
	 * @return array	An associative array of request information
	 */
	public function getRequest()
	{
		if(!$this->_request)
		{
			if(KInflector::isPlural(KRequest::get('get.view', 'cmd'))) {
				$request = KRequest::get('request', 'string');
			} else {
				$request = KRequest::get('get', 'string');
			}
		
			// Built the session identifier based on the action
			$identifier  = KFactory::get($this->getModel())->getIdentifier().'.'.$this->_action;
			$state       = KRequest::get('session.'.$identifier, 'raw', array());
			
			$this->_request = KHelperArray::merge($state, $request);
		}
			
		return $this->_request;
	}
	
	/**
	 * Set the request information
	 *
	 * @param array	An associative array of request information 
	 * @return KControllerBread
	 */
	public function setRequest(array $request)
	{	
		$this->_request = $request;
		return $this;
	}
	
	/**
	 * Saves the model state in the session
	 *
	 * @return KControllerBread
	 */
	public function saveModelState()
	{
		$model  = KFactory::get($this->getModel());
		$state  = $model->get();
		
		// Built the session identifier based on the action
		$identifier  = $model->getIdentifier().'.'.$this->_action;	
		
		//Set the state in the session
		KRequest::set('session.'.$identifier, $state);
		
		return $this;
	}
	
	/**
	 * Get the identifier for the view with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getView()
	{
		if(!$this->_view)
		{
			$identifier			= clone $this->_identifier;
			$identifier->path	= array('view', KRequest::get('get.view', 'cmd', $identifier->name));
			$identifier->name	= KRequest::get('get.format', 'cmd', 'html');
		
			$this->_view = $identifier;
		}	
		
		return $this->_view;
	}
	
	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a view identifier
	 * @return	KControllerAbstract
	 */
	public function setView($view)
	{
		$identifier = KFactory::identify($view);

		if($identifier->path[0] != 'view') {
			throw new KControllerException('Identifier: '.$identifier.' is not a view identifier');
		}
		
		$this->_view = $identifier;
		return $this;
	}

	/**
	 * Get the identifier for the model with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getModel()
	{
		if(!$this->_model)
		{
			$identifier			= clone $this->_identifier;
			$identifier->path	= array('model');

			// Models are always plural
			$identifier->name	= KInflector::isPlural($identifier->name) ? $identifier->name : KInflector::pluralize($identifier->name);
		
			$this->_model = $identifier;
		}
		
		return $this->_model;
	}
	
	/**
	 * Method to set a model object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a model identifier
	 * @return	KControllerAbstract
	 */
	public function setModel($model)
	{
		$identifier = KFactory::identify($model);

		if($identifier->path[0] != 'model') {
			throw new KControllerException('Identifier: '.$identifier.' is not a model identifier');
		}
		
		$this->_model = $identifier;
		return $this;
	}
	
	/**
	 * Display the view
	 *
	 * @return void
	 */
	public function displayView()
	{
		KFactory::get($this->getView())
			->setLayout(KRequest::get('get.layout', 'cmd', 'default' ))
			->display();
	}
	
	/**
	 * Browse a list of items
	 *
	 * @return KDatabaseRowset	A rowset object containing the selected rows
	 */
	protected function _actionBrowse()
	{
		$rowset = KFactory::get($this->getModel())
					->getList();
			
		return $rowset;
	}

	/**
	 * Display a single item
	 *
	 * @return KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead()
	{		
		$row = KFactory::get($this->getModel())
					->getItem();
					
		return $row;
	}

	/**
	 * Generic edit action, saves over an existing item
	 * 
	 * @param	mixed 	Either a scalar, an associative array, an object 
	 * 					or a KDatabaseRow
	 * @return KDatabaseRowset 	A rowset object containing the updated rows
	 */
	protected function _actionEdit($data)
	{		
		$rowset = KFactory::get($this->getModel())
				->getList()
				->setData($data)
				->save();

		return $rowset;
	}

	/**
	 * Generic add action, saves a new item
	 * 
	 * @param	mixed 	Either a scalar, an associative array, an object 
	 * 					or a KDatabaseRow
	 * @return KDatabaseRow 	A row object containing the new data
	 */
	protected function _actionAdd($data)
	{
		$row = KFactory::get($this->getModel())
				->getItem()
				->setData($data)
				->save();

		return $row;
	}

	/**
	 * Generic delete function
	 *
	 * @return KDatabaseRowset	A rowset object containing the deleted rows
	 */
	protected function _actionDelete()
	{	
		$rowset = KFactory::get($this->getModel())
					->getList()
					->delete();
			
		return $rowset;
	}
}