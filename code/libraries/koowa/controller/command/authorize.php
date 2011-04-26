<?php
/**
 * @version		$Id: abstract.php 3107 2011-04-25 00:58:59Z johanjanssens $
 * @category	Koowa
 * @package		Koowa_Controller
 * @subpackage	Command
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Default Controller Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @subpackage	Command
 */
class KControllerCommandAuthorize extends KCommand
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
                $context->error = new KControllerException(
            		'Action : "'.$action.'" Not Implemented', KHttpResponse::NOT_IMPLEMENTED
                );
                
                return false;
            }
               
            //Check the ACL rules
            $method = 'can'.ucfirst($action);
            if(in_array($method, $this->getMethods())) 
            {                
                if($this->$method($context) === false) 
                {
                    $context->error = new KControllerException(
                       'Action : "'.$action.'" Not Allowed', KHttpResponse::METHOD_NOT_ALLOWED
                    );
                
                    return false;
                }
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
    public function canPut(KCommandContext $context)
    {
	    if(!$context->caller->getModel()->getState()->isUnique()) 
	    {  
	         $context->error = new KControllerException(
                ucfirst($context->caller->getIdentifier()->name).' not found', KHttpResponse::BAD_REQUEST
            );
            
            return false;
	    }
    }
}