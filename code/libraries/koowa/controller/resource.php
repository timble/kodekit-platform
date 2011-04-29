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
abstract class KControllerResource extends KControllerPage
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

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
    		'persistent'	=> false,
        ));

        parent::_initialize($config);
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
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a view identifier
	 * @return	KControllerAbstract
	 */
    public function setView($view)
	{
	    if(is_string($view) && strpos($view, '.') === false ) 
		{	
		    if(!isset($this->_request->view)) 
		    { 
		        if($this->getModel()->getState()->isUnique()) {
			        $view = KInflector::singularize($view);
		        } else {
			        $view = KInflector::pluralize($view);
	    	    }
		    }
		}
		
		return parent::setView($view);
	}
	
	/**
	 * Browse a list of items
	 * 
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRowset	A rowset object containing the selected rows
	 */
	protected function _actionBrowse(KCommandContext $context)
	{
		$data = $this->getModel()->getList();
		return $data;
	}

	/**
	 * Display a single item
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead(KCommandContext $context)
	{
	    $data = $this->getModel()->getItem();
	    $name = ucfirst($this->getView()->getName());
	    	
		if($this->getModel()->getState()->isUnique() && $data->isNew()) {
		    $context->setError(new KControllerException($name.' Not Found', KHttpResponse::NOT_FOUND));
		} 
		
		return $data;
	}

	/**
	 * Generic edit action, saves over an existing item
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRowset A rowset object containing the updated rows
	 */
	protected function _actionEdit(KCommandContext $context)
	{ 
	    $data = $this->getModel()->getData();
	    $name = ucfirst($this->getView()->getName());
								
	    if(count($data)) 
	    {
	        $data->setData(KConfig::toData($context->data));
	        
	        if($data->save()) {
		        $context->status = KHttpResponse::RESET_CONTENT;
		    } else {
		        $context->status = KHttpResponse::NO_CONTENT;
		    }
		} 
		else $context->setError(new KControllerException($name.' Not Found', KHttpResponse::NOT_FOUND));
					
		return $data;
	}

	/**
	 * Generic add action, saves a new item
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow 	A row object containing the new data
	 */
	protected function _actionAdd(KCommandContext $context)
	{
		$data = $this->getModel()->getItem();
		$name = ucfirst($this->getView()->getName());
				
		if($data->isNew())	
		{	
		    $data->setData(KConfig::toData($context->data));
		    
		    if(!$data->save()) 
		    {    
		        $context->setError(new KControllerException(
		           $name.' Add Action Failed', KHttpResponse::INTERNAL_SERVER_ERROR
		        ));
		       
		    } 
		    else $context->status = KHttpResponse::CREATED;
		} 
		else $context->setError(new KControllerException($name.' Already Exists', KHttpResponse::BAD_REQUEST));
				
		return $data;
	}

	/**
	 * Generic delete function
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRowset	A rowset object containing the deleted rows
	 */
	protected function _actionDelete(KCommandContext $context)
	{
	    $data = $this->getModel()->getData();
	    $name = ucfirst($this->getView()->getName());
							
		if(count($data)) 
	    {
            $data->setData(KConfig::toData($context->data));
	        
	        if(!$data->delete()) 
	        {
                 $context->setError(new KControllerException(
		             $name.' Delete Action Failed', KHttpResponse::INTERNAL_SERVER_ERROR
		         ));  
		    } 
		    else  $context->status = KHttpResponse::NO_CONTENT;
		} 
		else  $context->setError(new KControllerException($name.' Not Found', KHttpResponse::NOT_FOUND));
					
		return $data;
	}
	
	/**
	 * Get action
	 * 
	 * This function translates a GET request into a read or browse action. If the view name is 
	 * singular a read action will be executed, if plural a browse action will be executed.
	 * 
	 * This function will not render anything the result of the read or browse action is not a 
	 * row or rowset object
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	string|false 	The rendered output of the view or FALSE if something went wrong
	 */
	protected function _actionGet(KCommandContext $context)
	{
		//Check if we are reading or browsing
	    $action = KInflector::isSingular($this->getView()->getName()) ? 'read' : 'browse';
	    
	    //Execute the action
		$data = $this->execute($action, $context);
		
		//Only process the result if a valid row or rowset object has been returned
		if(($data instanceof KDatabaseRowInterface) || ($data instanceof KDatabaseRowsetInterface))
		{
            $view = $this->getView();
		   
            if($view instanceof KViewTemplate && isset($this->_request->layout)) {
                $view->setLayout($this->_request->layout);
            }
		    
            //Render the view
            $data = $view->display();
		}
		
		return $data;
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
	 * @param	KCommandContext			A command context object
	 * @return 	KDatabaseRow(set)		A row(set) object containing the modified data
	 * @throws  KControllerException 	If the model state is not unique 
	 */
	protected function _actionPut(KCommandContext $context)
	{   
	    $data = $this->getModel()->getItem();
	        
        $action = 'add';
	    if(!$data->isNew()) 
	    {
	        //Reset the row data
	        $data->reset();
	        $action = 'edit';
        }
	            
        //Set the row data based on the unique state information
	    $state = $this->getModel()->getState()->getData(true);
	    $data->setData($state);
	             
        return parent::execute($action, $context); 
	}
}