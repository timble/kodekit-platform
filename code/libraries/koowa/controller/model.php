<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Bread Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package		Koowa_Controller
 */
abstract class KControllerModel extends KControllerView
{
	/**
	 * Model identifier (APP::com.COMPONENT.model.NAME)
	 *
	 * @var	string|object
	 */
	protected $_model;
	
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Set the model identifier
		if(!empty($config->model)) {
			$this->setModel($config->model);
		}
		
		//Register the load and save request function to make the request persistent
		if($config->persistent)
		{
			$this->registerCallback('before.browse' , array($this, 'loadState'));
			$this->registerCallback('after.browse'  , array($this, 'saveState'));
		}	
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
    		'persistent'	=> false,
        ));

        parent::_initialize($config);
    }
    
    /**
	 * Push the request data into the model state
	 *
	 * @param	string		The action to execute
	 * @return	mixed|false The value returned by the called method, false in error case.
	 * @throws 	KControllerException
	 */
	public function execute($action, $data = null)
	{
		$this->getModel()->set($this->getRequest());

		return parent::execute($action, $data);
	}
   
	/**
	 * Load the model state from the request
	 *
	 * This functions merges the request information with any model state information
	 * that was saved in the session and returns the result.
	 *
	 * @param 	KCommandContext		The active command context
	 * @return array	An associative array of request information
	 */
	public function loadState(KCommandContext $context)
	{
		// Built the session identifier based on the action
		$identifier  = $this->getModel()->getIdentifier().'.'.$this->_action;
		$state       = KRequest::get('session.'.$identifier, 'raw', array());
			
		//Append the data to the request object
		$this->_request->append($state);
		
		//Push the request in the model
		$this->getModel()->set($this->getRequest());
		
		return $this;
	}

	/**
	 * Saves the model state in the session
	 *
	 * @param 	KCommandContext		The active command context
	 * @return KControllerBread
	 */
	public function saveState(KCommandContext $context)
	{
		$model  = $this->getModel();
		$state  = $model->get();

		// Built the session identifier based on the action
		$identifier  = $model->getIdentifier().'.'.$this->_action;
		
		//Set the state in the session
		KRequest::set('session.'.$identifier, $state);

		return $this;
	}

	/**
	 * Get the identifier for the model with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getModel()
	{
		if(!$this->_model)
		{
			$identifier			= clone $this->_identifier;
			$identifier->path	= array('model');

			// Models are always plural
			$identifier->name	= KInflector::isPlural($identifier->name) ? $identifier->name : KInflector::pluralize($identifier->name);

			$this->_model = KFactory::get($identifier);
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
		if(!($model instanceof $model))
		{
			$identifier = KFactory::identify($model);

			if($identifier->path[0] != 'model') {
				throw new KControllerException('Identifier: '.$identifier.' is not a model identifier');
			}

			$model = KFactory::get($identifier);
		}
		
		$this->_model = $model;
		return $this;
	}

	/**
	 * Browse a list of items
	 * 
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRowset	A rowset object containing the selected rows
	 */
	protected function _actionBrowse(KCommandContext $context)
	{
		$rowset = $this->getModel()->getList();
		
		if(!count($rowset)) {
		    $context->status = KHttpResponse::NOT_FOUND;
		} 
		
		return $rowset;
	}

	/**
	 * Display a single item
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead(KCommandContext $context)
	{
	    $row = $this->getModel()->getItem();
	    		
		if($row->isNew()) {
		     $context->status = KHttpResponse::NOT_FOUND;
		} 
		
		return $row;
	}

	/**
	 * Generic edit action, saves over an existing item
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRowset A rowset object containing the updated rows
	 */
	protected function _actionEdit(KCommandContext $context)
	{ 
	    $rowset = $this->getModel()->getList();
								
	    if(count($rowset)) 
	    {
	        $rowset->setData(KConfig::toData($context->data));
	        
	        if($rowset->save()) {
		        $context->status = KHttpResponse::RESET_CONTENT;
		    } else {
		        $context->status = KHttpResponse::NO_CONTENT;
		    }
		} 
		else $context->status = KHttpResponse::NOT_FOUND;
					
		return $rowset;
	}

	/**
	 * Generic add action, saves a new item
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow 	A row object containing the new data
	 */
	protected function _actionAdd(KCommandContext $context)
	{
		$row = $this->getModel()->getItem();
				
		if($row->isNew())	
		{	
		    $row->setData(KConfig::toData($context->data));
		    
		    if($row->save()) {
		       $context->status = KHttpResponse::CREATED;
		    } else {
		        $context->status = KHttpResponse::INTERNAL_SERVER_ERROR;
		    }
		} 
		else $context->status = KHttpResponse::BAD_REQUEST;
				
		return $row;
	}

	/**
	 * Generic delete function
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRowset	A rowset object containing the deleted rows
	 */
	protected function _actionDelete(KCommandContext $context)
	{
	    $rowset = $this->getModel()->getList();
							
		if(count($rowset)) 
	    {
            $rowset->setData(KConfig::toData($context->data));
	        
	        if($rowset->delete()) {
                $context->status = KHttpResponse::NO_CONTENT;
		    } else {
		        $context->status = KHttpResponse::INTERNAL_SERVER_ERROR;
		    }
		} 
		else $context->status = KHttpResponse::NOT_FOUND;
					
		return $rowset;
	}
	
	/**
	 * Post action
	 * 
	 * This function translated a POST request action into an edit or add action. If the model 
	 * state is unique a edit action will be executed, if not unique an add action will 
	 * be executed.
	 *
	 * @param	KCommandContext		A command context object
	 * @return 	KDatabaseRow(set)	A row(set) object containing the modified data
	 */
	protected function _actionPost(KCommandContext $context)
	{
		$action = $this->getModel()->getState()->isUnique() ? 'edit' : 'add';
		return parent::execute($action, $context);
	}
	
	/**
	 * Put action
	 * 
	 * This function translates a PUT request into an edit or add action. Only if the model
	 * state is unique and the item exists an edit action will be executed, if the resources
	 * doesn't exist an add action will be executed.
	 * 
	 * If the resource already exists it will be completely replaced based on the data
	 * available in the request.
	 * 
	 * If the model state is not unique the function will return false and set the
	 * status code to 400 BAD REQUEST.
	 *
	 * @param	KCommandContext		A command context object
	 * @return 	KDatabaseRow(set)	A row(set) object containing the modified data
	 */
	protected function _actionPut(KCommandContext $context)
	{    
	    $result = false;
	    if($this->getModel()->getState()->isUnique()) 
	    {  
	        $row   = $this->getModel()->getItem();
	        
	        $action = 'add';
	        if(!$row->isNew()) 
	        {
	            //Reset the row data
	            $row->reset();
	            $action = 'edit';
	        }
	            
	        //Set the row data based on the unique state information
	        $state = $this->getModel()->getState()->getData(true);
	        $row->setData($state);
	             
	        $result  = parent::execute($action, $context); 
        } 
        else  $context->status = KHttpResponse::BAD_REQUEST;
      
        return $result;
	}
	
	/**
	 * Display action
	 * 
	 * This function translates a GET request into a read or browse action. If the view name is 
	 * singular a read action will be executed, if plural a browse action will be executed.
	 * 
	 * This function will not render anything if the following conditions are met :
	 * 
	 * - The result of the read or browse action is not a row or rowset object
	 * - The contex::status is 404 NOT FOUND and the view is not a HTML view
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	string|false 	The rendered output of the view or FALSE if something went wrong
	 */
	protected function _actionDisplay(KCommandContext $context)
	{
		//Check if we are reading or browsing
	    $action = KInflector::isSingular($this->getView()->getName()) ? 'read' : 'browse';
	    
	    //Execute the action
		$result = $this->execute($action, $context);
		
		//Only process the result if a valid row or rowset object has been returned
		if(($result instanceof KDatabaseRowInterface) || ($result instanceof KDatabaseRowsetInterface))
		{
            $view = $this->getView();
		   
            if(($context->status != KHttpResponse::NOT_FOUND) || $view instanceof KViewHtml)
            {
		        if($view instanceof KViewTemplate && isset($this->_request->layout)) {
			        $view->setLayout($this->_request->layout);
		        }
		    
		        $result = $view->display();
             }
             else $result = false;
		}
		
		return $result;
	}
	
	/**
	 * Supports a simple form Fluent Interfaces. Allows you to set the request 
	 * properties by using the request property name as the method name.
	 *
	 * For example : $controller->view('name')->limit(10)->browse();
	 *
	 * @param	string	Method name
	 * @param	array	Array containing all the arguments for the original call
	 * @return	KControllerBread
	 *
	 * @see http://martinfowler.com/bliki/FluentInterface.html
	 */
	public function __call($method, $args)
	{
		//Check first if we are calling a mixed in method. This prevents the model being 
		//loaded durig object instantiation. 
		if(!isset($this->_mixed_methods[$method])) 
        {
			//Check if the method is a state property
			$state = $this->getModel()->getState();
		
			if(isset($state->$method)) 
			{
				$this->$method = $args[0];
				return $this;
			}
        }
		
		return parent::__call($method, $args);
	}
}