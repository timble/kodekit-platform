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
 * Abstract View Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @uses        KInflector
 */
abstract class KControllerView extends KControllerAbstract
{
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
	 * View identifier (APP::com.COMPONENT.view.NAME.FORMAT)
	 *
	 * @var	string|object
	 */
	protected $_view;

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
        	'view'	  => null,
    	    'action'  => 'display',
        ));

        parent::_initialize($config);
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
			
			//Enable the auto-filtering if the controller was dispatched or if the MVC triad was
			//called outside of the dispatcher.
			$config = array(
			    'auto_filter'  => $this->isDispatched() || !KFactory::has('lib.koowa.dispatcher')
        	);
			
			$this->_view = KFactory::tmp($identifier, $config);
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
	 * Specialised display function.
	 *
	 * @param	KCommandContext	A command context object
	 * @return 	string|false 	The rendered output of the view or false if something went wrong
	 */
	protected function _actionDisplay(KCommandContext $context)
	{
		$view = $this->getView();
	
        //Set the layout in the view
	    if(isset($this->_request->layout)) {
            $view->setLayout($this->_request->layout);
	     }
	     
        //Render the view and return the output
		return $view->display();
	}
	
	/**
	 * Supports a simple form Fluent Interfaces. Allows you to set the request 
	 * properties by using the request property name as the method name.
	 *
	 * For example : $controller->view('name')->layout('form')->display();
	 *
	 * @param	string	Method name
	 * @param	array	Array containing all the arguments for the original call
	 * @return	KControllerBread
	 *
	 * @see http://martinfowler.com/bliki/FluentInterface.html
	 */
	public function __call($method, $args)
	{
		//Check first if we are calling a mixed in method.
		if(!isset($this->_mixed_methods[$method])) 
        {
			if(in_array($method, array('layout', 'view', 'format'))) 
			{
				$this->$method = $args[0];
				return $this;
			}
        }
		
		return parent::__call($method, $args);
	}
}