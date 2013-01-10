<?php
/**
 * @version		$Id$
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Controller Class
 *
 * Note: Concrete controllers must have a singular name
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @uses        KMixinClass
 * @uses        KCommandChain
 */
abstract class KControllerAbstract extends KObject
{
    /**
     * Array of class methods to call for a given action.
     *
     * @var array
     */
    protected $_action_map = array();

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
	 * List of behaviors
	 *
	 * Associative array of behaviors, where key holds the behavior identifier string
	 * and the value is an identifier object.
	 *
	 * @var	array
	 */
	protected $_behaviors = array();

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

         //Set the dispatched state
        $this->_dispatched = $config->dispatched;

        // Mixin the command chain
        $this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));

        // Set the table behaviors
        if(!empty($config->behaviors)) {
            $this->addBehavior($config->behaviors);
        }

        //Set the request
		$this->setRequest((array) KConfig::unbox($config->request));
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
            'dispatch_events'   => true,
            'enable_callbacks'  => true,
            'dispatched'		=> false,
            'request'		    => null,
            'behaviors'         => array(),
        ));

        parent::_initialize($config);
    }

	/**
     * Has the controller been dispatched
     *
     * @return  boolean	Returns true if the controller has been dispatched
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

        //Update the context
        $context->action = $action;
        $context->caller = $this;

        //Find the mapped action
        if (isset( $this->_action_map[$action] )) {
           $command = $this->_action_map[$action];
        } else {
           $command = $action;
        }

        //Execute the action
        if($this->getCommandChain()->run('before.'.$command, $context) !== false)
        {
            $method = '_action'.ucfirst($command);

            if(!method_exists($this, $method))
            {
                //Lazy mix behaviors
                if(!isset($this->_mixed_methods[$method]))
		        {
			        foreach($this->getBehaviors() as $behavior) {
				        $this->mixin($behavior);
			        }
		        }

                if(isset($this->_mixed_methods[$command])) {
                    $context->result = $this->_mixed_methods[$command]->execute('action.'.$command, $context);
                } else {
                    throw new KControllerException("Can't execute '$command', method: '$method' does not exist");
                }
            }
            else  $context->result = $this->$method($context);

            $this->getCommandChain()->run('after.'.$command, $context);
        }

        //Handle exceptions
        if($context->getError() instanceof KException)
        {
            //@TODO : Move header handling into a response object
            if($context->headers)
	        {
	            foreach($context->headers as $name => $value) {
	                header($name.' : '.$value);
	            }
	        }

            throw $context->getError();
        }

        return $context->result;
    }

    /**
     * Gets the available actions in the controller.
     *
     * @return  array Array[i] of action names.
     */
    public function getActions($reload = false)
    {
        if(!$this->_actions || $reload)
        {
            $this->_actions = array();

            foreach($this->getMethods() as $method)
            {
                if(substr($method, 0, 7) == '_action') {
                    $this->_actions[] = strtolower(substr($method, 7));
                }
            }

            foreach($this->_behaviors as $behavior)
            {
                foreach($behavior->getMethods() as $method)
                {
                    if(substr($method, 0, 7) == '_action') {
                        $this->_actions[] = strtolower(substr($method, 7));
                    }
                }
            }

            $this->_actions = array_unique(array_merge($this->_actions, array_keys($this->_action_map)));
        }

        return $this->_actions;
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
		$this->_request = new KConfig();
		foreach($request as $key => $value) {
		    $this->$key = $value;
		}

		return $this;
	}

	/**
     * Check if a behavior exists
     *
     * @param 	string	The name of the behavior
     * @return  boolean	TRUE if the behavior exists, FALSE otherwise
     */
	public function hasBehavior($behavior)
	{
	    return isset($this->_behaviors[$behavior]);
	}

	/**
     * Add one or more behaviors to the controller
     *
     * @param   array   Array of one or more behaviors to add.
     * @return  KControllerAbstract
     */
    public function addBehavior($behaviors)
    {
        $behaviors = (array) KConfig::unbox($behaviors);

        foreach($behaviors as $behavior)
        {
            if (!($behavior instanceof KControllerBehaviorInterface)) {
                $behavior = $this->getBehavior($behavior);
            }

            //Add the behaviors
            $this->_behaviors[$behavior->getIdentifier()->name] = $behavior;

            if($this->getCommandChain()->enqueue($behavior)) {
                $this->_actions = null; //reset the actions
            }
        }

        return $this;
    }

	/**
     * Get a behavior by identifier
     *
     * @return KControllerBehaviorAbstract
     */
    public function getBehavior($behavior, $config = array())
    {
       if(!($behavior instanceof KServiceIdentifier))
       {
            //Create the complete identifier if a partial identifier was passed
           if(is_string($behavior) && strpos($behavior, '.') === false )
           {
               $identifier = clone $this->getIdentifier();
               $identifier->path = array('controller', 'behavior');
               $identifier->name = $behavior;
           }
           else $identifier = $this->getIdentifier($behavior);
       }

       if(!isset($this->_behaviors[$identifier->name]))
       {
           $behavior = $this->getService($identifier, array_merge($config, array('mixer' => $this)));

           //Check the behavior interface
		   if(!($behavior instanceof KControllerBehaviorInterface)) {
			   throw new KControllerBehaviorException("Controller behavior $identifier does not implement KControllerBehaviorInterface");
		   }
       }
       else $behavior = $this->_behaviors[$identifier->name];

       return $behavior;
    }

    /**
     * Gets the behaviors of the table
     *
     * @return array    An asscociate array of table behaviors, keys are the behavior names
     */
    public function getBehaviors()
    {
        return $this->_behaviors;
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

        //Force reload of the actions
        $this->getActions(true);

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
	 * Function is also capable of checking is a behavior has been mixed succesfully
	 * using is[Behavior] function. If the behavior exists the function will return
	 * TRUE, otherwise FALSE.
     *
     * @param   string  Method name
     * @param   array   Array containing all the arguments for the original call
     * @see execute()
     */
    public function __call($method, $args)
    {
        //Handle action alias method
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

        //Check if a behavior is mixed
		$parts = KInflector::explode($method);

		if($parts[0] == 'is' && isset($parts[1]))
		{
		    //Lazy mix behaviors
		    $behavior = strtolower($parts[1]);

            if(!isset($this->_mixed_methods[$method]))
            {
                if($this->hasBehavior($behavior))
                {
                    $this->mixin($this->getBehavior($behavior));
                    return true;
		        }

			    return false;
            }

            return true;
		}

        return parent::__call($method, $args);
    }
}