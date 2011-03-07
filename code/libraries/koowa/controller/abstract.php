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
        
        // Mixin the command chain
        $this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));
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
            'enable_callbacks'  => true
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
     * Execute an action by triggering a method in the derived class.
     *
     * @param   string      The action to execute
     * @param   array       The data to pass to the action method
     * @return  mixed|false The value returned by the called method, false in error case.
     * @throws  KControllerException
     */
    public function execute($action, $data = null)
    {
        $action = strtolower($action);
        
        //Set the original action in the controller to allow it to be retrieved
        $this->setAction($action);

        //Create the command context object
        if(!($data instanceof KCommandContext))
        {
            $context = $this->getCommandContext();
            $context->data   = $data;
            $context->result = false;
        } 
        else $context = $data;
        
        //Set the action
        $context->action = $action;
        
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
     * Execute a controller action by it's name. 
     * 
     * @param   string  Method name
     * @param   array   Array containing all the arguments for the original call
     * @see execute()
     */
    public function __call($method, $args)
    {
        if(in_array($method, $this->getActions())) {
            return $this->execute($method, !empty($args) ? $args[0] : null);
        }
        
        return parent::__call($method, $args);
    }
}