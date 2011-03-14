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
     * Command handler
     * 
     * @param   string      The command name
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    public function execute( $name, KCommandContext $context) 
    {
        $result = parent::execute($name, $context);
        if($result == false) {
            $context->status = KHttpResponse::FORBIDDEN;
        }
        
        return $result; 
    }
    
    /**
     * Generic authorize handler for controller add actions
     * 
     * @param   object      The command context
     * @return  boolean     Can return both true or false.  
     */
    public function _controllerBeforeAdd(KCommandContext $context)
    {
        if(JVERSION::isCompatible('1.6')) {
            $result = KFactory::get('lib.koowa.user')->authorise('core.create');
        } else {
            $result = KFactory::get('lib.koowa.user')->get('gid') > 22;
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
        if(JVERSION::isCompatible('1.6')) {
            $result = KFactory::get('lib.koowa.user')->authorise('core.edit');
        } else {
            $result = KFactory::get('lib.koowa.user')->get('gid') > 22;
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
        if(JVERSION::isCompatible('1.6')) {
            $result = KFactory::get('lib.koowa.user')->authorise('core.delete');
        } else {
            $result = KFactory::get('lib.koowa.user')->get('gid') > 22;
        }
          
        return $result;
    }
}