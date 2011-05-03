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
 * Controller Authorize Command Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @subpackage	Behavior
 */
class KControllerBehaviorExecutable extends KControllerBehaviorAbstract
{  
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
            'priority'   => KCommand::PRIORITY_HIGH,
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
            		'Action Not Implemented', KHttpResponse::NOT_IMPLEMENTED
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
		        		'Action Not Allowed', KHttpResponse::METHOD_NOT_ALLOWED
		            ));
		        
		            $context->header = array('Allow' =>  $context->caller->execute('options', $context));  
		        }
                    
		        return false;
		    }
        } 
            
        return true; 
    }
    
    /**
     * Generic authorize handler for controller put actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforePut(KCommandContext $context)
    {  
        if(!$context->caller->getModel()->getState()->isUnique()) 
	    {  
	         $context->setError(new KControllerException(
                ucfirst($context->caller->getIdentifier()->name).' not found', KHttpResponse::BAD_REQUEST
            ));
            
            return false;
	    }
    }
}