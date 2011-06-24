<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * User Controller Executable Behavior 
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerBehaviorExecutable extends ComDefaultControllerBehaviorExecutable
{  
    public function canLogout()
    {
        $userid = KFactory::get('lib.joomla.user')->id;
        
        //Allow logging out ourselves
        if($this->getModel()->getState()->id === $userid) {
             return true;
        }
        
        if(KFactory::get('lib.joomla.user')->get('gid') > 24) {
            return true;
        }
        
        return false;
    }
}