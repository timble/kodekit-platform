<?php
/**
 * @version     $Id: link.php 2876 2011-03-07 22:19:20Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Authorization Command
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultCommandAuthorize extends KCommand
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
     */
    public function execute( $name, KCommandContext $context) 
    { 
        $parts = explode('.', $name); 
        
        //Check the token
        if($parts[0] == 'before' && $context->caller->isDispatched()) 
        {
            if(!$this->checkToken()) {
                throw new KControllerException('Invalid token or session time-out', KHttpResponse::UNAUTHORIZED);
            }
        }
       
        //Execute the command
        if(parent::execute($name, $context) == false) {
             throw new KControllerException(ucfirst($context->action).' action not allowed', KHttpResponse::UNAUTHORIZED);
        }
        
        return true; 
    }
    
    /**
     * Generic authorize handler for controller add actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    public function _controllerBeforeAdd(KCommandContext $context)
    {
        if(version_compare(JVERSION,'1.6.0','ge')) {
            $result = KFactory::get('lib.joomla.user')->authorise('core.create');
        } else {
            $result = KFactory::get('lib.joomla.user')->get('gid') > 22;
        }
        
        return $result;
    }
    
  	/**
     * Generic authorize handler for controller edit actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    public function _controllerBeforeEdit(KCommandContext $context)
    {
        if(version_compare(JVERSION,'1.6.0','ge')) {
            $result = KFactory::get('lib.joomla.user')->authorise('core.edit');
        } else {
            $result = KFactory::get('lib.joomla.user')->get('gid') > 22;
        }
          
        return $result;
    }
    
    /**
     * Generic authorize handler for controller delete actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    public function _controllerBeforeDelete(KCommandContext $context)
    {
        if(version_compare(JVERSION,'1.6.0','ge')) {
            $result = KFactory::get('lib.joomla.user')->authorise('core.delete');
        } else {
            $result = KFactory::get('lib.joomla.user')->get('gid') > 22;
        }
          
        return $result;
    }
    
    /**
     * Check the token
     * 
     * @return  boolean Returns FALSE if the token is not valid or the session timed-out. 
     */
    public function checkToken()
    {
        if((KRequest::method() != KHttpRequest::GET)) 
        {     
            if( KRequest::token() !== JUtility::getToken()) {
                return false;
            }
        }
        
        return true;
    }
}