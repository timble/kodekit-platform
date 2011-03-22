<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Action Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KInflector
 */
abstract class KControllerView extends KControllerBread
{
	/**
	 * View identifier (APP::com.COMPONENT.view.NAME.FORMAT)
	 *
	 * @var	string|object
	 */
	protected $_view;

	/**
	 * URL for redirection.
	 *
	 * @var	string
	 */
	protected $_redirect = null;

	/**
	 * Redirect message.
	 *
	 * @var	string
	 */
	protected $_redirect_message = null;

	/**
	 * Redirect message type.
	 *
	 * @var	string
	 */
	protected $_redirect_type = 'message';

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		 // Set the view identifier
		if(!empty($config->view)) {
			$this->setView($config->view);
		}
		
		$this->registerActionAlias('get', 'display');

		$this->registerCallback('before.read'  , array($this, 'saveReferrer'));
		$this->registerCallback('before.browse', array($this, 'saveReferrer'));
		
		$this->registerCallback('after.read'  , array($this, 'lockView'));
		$this->registerCallback('after.edit'  , array($this, 'unlockView'));
		$this->registerCallback('after.cancel', array($this, 'unlockView'));

		//Set default redirect
		$this->_redirect = KRequest::referrer();
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
        	'view'	=> null,
        ));

        parent::_initialize($config);
    }
   
	/**
	 * Store the referrer in the session
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function saveReferrer(KCommandContext $context)
	{								
		$referrer = KRequest::referrer();
		
		if(isset($referrer) && KRequest::type() == 'HTTP')
		{
			$request  = KRequest::url();
			
			$request->get(KHttpUri::PART_PATH | KHttpUri::PART_QUERY);
			$referrer->get(KHttpUri::PART_PATH | KHttpUri::PART_QUERY);
			
			//Compare request url and referrer
			if($request != $referrer) {
				KRequest::set('session.com.controller.referrer', (string) $referrer);
			}
		}
	}
	
	/**
	 * Lock callback
	 * 
	 * Only lock if the context contains a row object and the view layout is 'form'. 
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function lockView(KCommandContext $context)
	{								
       if($context->result instanceof KDatabaseRowInterface) 
       {
	        $view = $this->getView();
	    
	        if($view instanceof KViewTemplate)
	        {
                if($view->getLayout() == 'form' && $context->result->isLockable()) {
		            $context->result->lock();
		        }
            }
	    }
	}
	
	/**
	 * Unlock callback
	 *
	 * @param 	KCommandContext		The active command context
	 * @return void
	 */
	public function unlockView(KCommandContext $context)
	{								  
	    if($context->result->isLockable()) {
			$context->result->unlock();
		}
	}
	
	/**
	 * Get the identifier for the view with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getView()
	{
		if(!$this->_view)
		{
			if(!isset($this->_request->view))
			{
				$name = $this->_identifier->name;
				if($this->getModel()->getState()->isUnique()) {
					$this->_request->view = $name;
				} else {
					$this->_request->view = KInflector::pluralize($name);
				}
			}

			$identifier			= clone $this->_identifier;
			$identifier->path	= array('view', $this->_request->view);
			$identifier->name	= KRequest::format() ? KRequest::format() : 'html';
			
			$config = array(
			    'auto_filter'  => $this->isDispatched()
        	);
			
			$this->_view = KFactory::get($identifier, $config);
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
		if(!($view instanceof KViewAbstract))
		{
			$identifier = KFactory::identify($view);

			if($identifier->path[0] != 'view') {
				throw new KControllerException('Identifier: '.$identifier.' is not a view identifier');
			}

			$this->_view = $view;
		}
		
		$this->_view = $view;
		return $this;
	}

	/**
	 * Set a URL for browser redirection.
	 *
	 * @param	string URL to redirect to.
	 * @param	string	Message to display on redirect. Optional, defaults to
	 * 			value set internally by controller, if any.
	 * @param	string	Message type. Optional, defaults to 'message'.
	 * @return	KControllerAbstract
	 */
	public function setRedirect( $url, $msg = null, $type = 'message' )
	{
		$this->_redirect   		 = $url;
		$this->_redirect_message = $msg;
		$this->_redirect_type	 = $type;

		return $this;
	}

	/**
	 * Returns an array with the redirect url, the message and the message type
	 *
	 * @return array	Named array containing url, message and messageType, or null if no redirect was set
	 */
	public function getRedirect()
	{
		$result = array();

		if(!empty($this->_redirect))
		{
			$result = array(
				'url' 		=> JRoute::_($this->_redirect, false),
				'message' 	=> $this->_redirect_message,
				'type' 		=> $this->_redirect_type,
			);
		}

		return $result;
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
	 * Save action
	 * 
	 * This function wraps around the edit or add action. If the model state is
	 * unique a edit action will be executed, if not unique an add action will be
	 * executed.
	 * 
	 * This function also sets the redirect to the referrer.
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionSave(KCommandContext $context)
	{
		$action = $this->getModel()->getState()->isUnique() ? 'edit' : 'add';
		$result = parent::execute($action, $context);
	    
		//Create the redirect
		$this->_redirect = KRequest::get('session.com.controller.referrer', 'url');
		return $result;
	}

	/**
	 * Apply action
	 * 
	 * This function wraps around the edit or add action. If the model state is
	 * unique a edit action will be executed, if not unique an add action will be
	 * executed.
	 * 
	 * This function also sets the redirect to the current url
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow 	A row object containing the saved data
	 */
	protected function _actionApply(KCommandContext $context)
	{
		$action = $this->getModel()->getState()->isUnique() ? 'edit' : 'add';
		$result = parent::execute($action, $context);
		
		//Create the redirect
		$this->_redirect = KRequest::url();
		
		return $result;
	}
	
	/**
	 * Cancel action
	 * 
	 * This function will unlock the row(s) and set the redirect to the referrer
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	KDatabaseRow	A row object containing the data of the cancelled object
	 */
	protected function _actionCancel(KCommandContext $context)
	{
		//Don't pass through the command chain
		$row = parent::_actionRead($context);

		//Create the redirect
		$this->_redirect = KRequest::get('session.com.controller.referrer', 'url');
		return $row;
	}
}