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
 * Abstract Controller Class
 *
 * Note: Concrete controllers must have a singular name
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Controller
 * @uses        KMixinClass
 * @uses        KCommandChain
 * @uses        KObject
 * @uses        KFactory
 */
abstract class KControllerAbstract extends KObject implements KObjectIdentifiable
{
    /**
     * Array of class methods to call for a given action.
     *
     * @var array
     */
    protected $_action_map = array();

    /**
     * Current or most recent action to be performed.
     *
     * @var string
     */
    protected $_action;
    
    /**
     * The class actions
     *
     * @var array
     */
    protected $_actions;
    
    /**
     * Has the controller been dispatched
     *
     * @var boolean
     */
    protected $_dispatched;
    
    /**
	 * The request information
	 *
	 * @var array
	 */
	protected $_request = null;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options.
     */
    public function __construct( KConfig $config = null)
    {
        //If no config is passed create it
        if(!isset($config)) $config = new KConfig();
        
        parent::__construct($config);
        
        //Set the action
        $this->_action = $config->action;
        
         //Set the dispatched state
        $this->_dispatched = $config->dispatched;
        
        // Mixin the command chain
        $this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));
        
        //Set the request
		$this->setRequest((array) KConfig::toData($config->request));
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'command_chain'     =>  new KCommandChain(),
            'action'            => null,
            'dispatch_events'   => true,
            'enable_callbacks'  => true,
            'dispatched'		=> false,
            'request'		=> null,
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Get the object identifier
     * 
     * @return  KIdentifier 
     * @see     KObjectIdentifiable
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }
    
	/**
     * Has the controller been dispatched
     * 
     * @return  boolean	Returns true if the controller has been dispatched
     * @see     KObjectIdentifiable
     */
    public function isDispatched()
    {
        return $this->_dispatched;
    }

    /**
     * Execute an action by triggering a method in the derived class.
     *
     * @param   string      The action to execute
     * @param   object		A command context object
     * @return  mixed|false The value returned by the called method, false in error case.
     * @throws  KControllerException
     */
    public function execute($action, KCommandContext $context)
    {
        $action = strtolower($action);
        
        //Set the original action in the controller to allow it to be retrieved
        $this->setAction($action);

        //Update the context
        $context->action = $action;
        $context->caller = $this;
        
        //Find the mapped action if one exists
        if (isset( $this->_action_map[$action] )) {
            $command = $this->_action_map[$action];
        } else {
            $command = $action;
        }
        
        if($this->getCommandChain()->run('before.'.$command, $context) !== false) 
        {
            $action = $context->action;
            $method = '_action'.ucfirst($command);
    
            if (!in_array($method, $this->getMethods())) {
                throw new KControllerException("Can't execute '$action', method: '$method' does not exist");
            }
                
            $context->result = $this->$method($context);
            $this->getCommandChain()->run('after.'.$command, $context);
        }

        return $context->result;
    }

    /**
     * Gets the available actions in the controller.
     *
     * @return  array Array[i] of action names.
     */
    public function getActions()
    {
        if(!$this->_actions)
        {
            $this->_actions = array();
            
            foreach($this->getMethods() as $action)
            {
                if(substr($action, 0, 7) == '_action') {
                    $this->_actions[] = strtolower(substr($action, 7));
                }
            
                $this->_actions = array_unique(array_merge($this->_actions, array_keys($this->_action_map)));
            }
        }
        
        return $this->_actions;
    }

    /**
     * Get the action that is was/will be performed.
     *
     * @return   string Action name
     */
    public function getAction()
    {
        return $this->_action;
    }

    /** 
     * Set the action that will be performed. 
     * 
     * @param       string Action name 
     * @return  KControllerAbstract 
     */ 
    public function setAction($action) 
    { 
        $action = strtolower($action);
        
        //Find the mapped action if one exists 
        if (isset( $this->_action_map[$action] )) { 
            $action = $this->_action_map[$action]; 
        } 
        
        $this->_action = $action; 
        return $this; 
    } 
    
	/**
	 * Get the request information
	 *
	 * @return KConfig	A KConfig object with request information
	 */
	public function getRequest()
	{
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
		$this->_request = new KConfig($request);
		return $this;
	}

    /**
     * Register (map) an action to a method in the class.
     *
     * @param   string  The action.
     * @param   string  The name of the method in the derived class to perform
     *                  for this action.
     * @return  KControllerAbstract
     */
    public function registerActionAlias( $alias, $action )
    {
        $alias = strtolower( $alias ); 
        
        if ( !in_array($alias, $this->getActions()) )  { 
            $this->_action_map[$alias] = $action; 
        } 
    
        return $this;
    }

    /**
     * Unregister (unmap) an action
     *
     * @param   string  The action
     * @return  KControllerAbstract
     */
    public function unregisterActionAlias( $action )
    {
        unset($this->_action_map[strtolower($action)]);
        return $this;
    }
    
	/**
     * Set a request properties
     *
     * @param  	string 	The property name.
     * @param 	mixed 	The property value.
     */
 	public function __set($property, $value)
    {
    	$this->_request->$property = $value;
  	}
  	
  	/**
     * Get a request property
     *
     * @param  	string 	The property name.
     * @return 	string 	The property value.
     */
    public function __get($property)
    {
    	$result = null;
    	if(isset($this->_request->$property)) {
    		$result = $this->_request->$property;
    	} 
    	
    	return $result;
    }

    /**
     * Execute a controller action by it's name. 
     * 
     * @param   string  Method name
     * @param   array   Array containing all the arguments for the original call
     * @see execute()
     */
    public function __call($method, $args)
    {
        if(in_array($method, $this->getActions())) 
        {
            //Get the data
            $data = !empty($args) ? $args[0] : array();
            
            //Create a context object
            if(!($data instanceof KCommandContext))
            {
                $context = $this->getCommandContext();
                $context->data   = $data;
                $context->result = false;
            } 
            else $context = $data;
            
            //Execute the action
            return $this->execute($method, $context);
        }
        
        return parent::__call($method, $args);
    }
}