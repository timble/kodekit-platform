<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @subpackage	Command
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Controller Executable Behavior Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @subpackage	Behavior
 */
class KControllerBehaviorExecutable extends KControllerBehaviorAbstract
{  
	/**
	 * The read-only state of the behavior
	 *
	 * @var boolean
	 */
	protected $_readonly;
		
	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null) 
	{ 
	    $this->_identifier = $config->identifier;
		parent::__construct($config);
		
		$this->_priority = $config->priority;
		$this->_readonly = (bool) $config->readonly;
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
            'priority'  => KCommand::PRIORITY_HIGH,
            'readonly'  => false
        ));

        parent::_initialize($config);
    }
	
	/**
     * Command handler
     * 
     * Only handles before.action commands to check ACL rules.
     * 
     * @param   string      The command name
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     * @throws  KControllerException
     */
    public function execute( $name, KCommandContext $context) 
    { 
        $parts = explode('.', $name); 
        
        if($parts[0] == 'before') 
        {
            $action = $parts[1];
            
            //Check if the action exists
            if(!in_array($action, $context->caller->getActions()))
            {
                $context->setError(new KControllerException(
            		'Action '.ucfirst($action).' Not Implemented', KHttpResponse::NOT_IMPLEMENTED
                ));
                
                $context->header = array('Allow' =>  $context->caller->execute('options', $context));
                return false;
            }
               
            //Check if the action can be executed
		    if(parent::execute($name, $context) === false) 
		    {
		        if($context->action != 'options') 
		        {
		            $context->setError(new KControllerException(
		        		'Action '.unfirst($action).' Not Allowed', KHttpResponse::METHOD_NOT_ALLOWED
		            ));
		        
		            $context->header = array('Allow' =>  $context->caller->execute('options', $context));  
		        }
                    
		        return false;
		    }
        } 
            
        return true; 
    }
    
    /**
     * Set the readonly state of the behavior
     * 
     * @param boolean
     * @return KControllerBehaviorExecutable
     */
    public function setReadOnly($readonly)
    {
         $this->_readonly = (bool) $readonly; 
         return $this;  
    }
    
    /**
     * Get the readonly state of the behavior
     *
     * @return boolean
     */
    public function isReadOnly()
    {
        return $this->readonly;
    }
    
 	/**
     * Generic authorize handler for controller add actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforeAdd(KCommandContext $context)
    {
        return !$this->_readonly;
    }
    
	/**
     * Generic authorize handler for controller edit actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforeEdit(KCommandContext $context)
    {
        return !$this->_readonly;
    }
    
 	/**
     * Generic authorize handler for controller delete actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforeDelete(KCommandContext $context)
    {
         return !$this->_readonly;
    }
    
    /**
     * Generic authorize handler for controller put actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforePut(KCommandContext $context)
    {  
        $result = false;
       
        if(!$this->_readonly)
        {
            if(!$context->caller->getModel()->getState()->isUnique()) 
	        {  
	             $context->setError(new KControllerException(
                    ucfirst($context->caller->getIdentifier()->name).' not found', KHttpResponse::BAD_REQUEST
                ));
	        }
	        
	        $result = true;
        }
        
        return $result;
    }
    
 	/**
     * Generic authorize handler for controller post actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforePost(KCommandContext $context)
    {  
        return !$this->_readonly;
    }
}