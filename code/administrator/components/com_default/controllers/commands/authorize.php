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
class ComDefaultControllerCommandAuthorize extends KControllerCommandAuthorize
{  
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
            //Check the token
            if($context->caller->isDispatched())
            {  
                if((KRequest::method() != KHttpRequest::GET)) 
                {     
                    if( KRequest::token() !== JUtility::getToken()) 
                    {
                        $context->error =  new KControllerException(
                        	'Invalid token or session time-out', KHttpResponse::FORBIDDEN
                        );
                        
                        return false;
                    }
                }
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
    public function canAdd(KCommandContext $context)
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
    public function canEdit(KCommandContext $context)
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
    public function canDelete(KCommandContext $context)
    {
        if(version_compare(JVERSION,'1.6.0','ge')) {
            $result = KFactory::get('lib.joomla.user')->authorise('core.delete');
        } else {
            $result = KFactory::get('lib.joomla.user')->get('gid') > 22;
        }
          
        return $result;
    }
}