<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Controller Authorization Command
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerBehaviorExecutable extends KControllerBehaviorExecutable
{ 
 	/**
     * Command handler
     * 
     * @param   string      The command name
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     * @throws  KControllerException
     */
    final public function execute( $name, KCommandContext $context) 
    { 
        $parts = explode('.', $name); 
        
        if($parts[0] == 'before') 
        { 
            if(!$this->_checkToken($context)) 
            {    
                $context->setError(new KControllerException(
                	'Invalid token or session time-out', KHttpResponse::FORBIDDEN
                ));
                
                return false;
            }
        }
        
        return parent::execute($name, $context); 
    }
   
    /**
     * Generic authorize handler for controller add actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforeAdd(KCommandContext $context)
    {
        $result = false;
        
        if(!$this->_readonly)
        {
            if(version_compare(JVERSION,'1.6.0','ge')) {
                $result = KFactory::get('lib.joomla.user')->authorise('core.create');
            } else {
                $result = KFactory::get('lib.joomla.user')->get('gid') > 18;
            }
        }
          
        return $result;
    }
    
    /**
     * Generic authorize handler for controller edit actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforeEdit(KCommandContext $context)
    {
        $result = false;
        
        if(!$this->_readonly)
        {
            if(version_compare(JVERSION,'1.6.0','ge')) {
                $result = KFactory::get('lib.joomla.user')->authorise('core.edit');
            } else {
                $result = KFactory::get('lib.joomla.user')->get('gid') > 19;
            }
        }
              
        return $result;
    }
    
    /**
     * Generic authorize handler for controller delete actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    protected function _beforeDelete(KCommandContext $context)
    {
        $result = false;
        
        if(!$this->_readonly)
        {
            if(version_compare(JVERSION,'1.6.0','ge')) {
                $result = KFactory::get('lib.joomla.user')->authorise('core.delete');
            } else {
                $result = KFactory::get('lib.joomla.user')->get('gid') > 20;
            }
        }
            
        return $result;
    }
    
	/**
	 * Check the token to prevent CSRF exploits
	 *
	 * @param   object  The command context
	 * @return  boolean Returns FAKSE if the check failed. Otherwise TRUE.
	 */
    protected function _checkToken(KCommandContext $context)
    {
        //Check the token
        if($context->caller->isDispatched())
        {  
            $method = KRequest::method();
            
            //Only check the token for PUT, DELETE and POST requests
            if(($method != KHttpRequest::GET) && ($method != KHttpRequest::OPTIONS)) 
            {     
                if( KRequest::token() !== JUtility::getToken()) {     
                    return false;
                }
            }
        }
        
        return true;
    }
}