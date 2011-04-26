<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Authorize Command
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerCommandAuthorize extends ComDefaultControllerCommandAuthorize
{
    public function canAdd(KCommandContext $context)
    {
        $parameters = JComponentHelper::getParams('com_users');

        if($parameters->get('allowUserRegistration') == '0') {
            return false;
        }
        
        return true;
    }
    
    public function canEdit(KCommandContext $context)
    {
        $request = $context->caller->getRequest();
        
        if($request->id == 0 || $request->id != KFactory::get('lib.joomla.user')->id) {
            return false;
        }
        
        $result = !KFactory::get('lib.joomla.user')->guest;       
        return $result;
    }
}