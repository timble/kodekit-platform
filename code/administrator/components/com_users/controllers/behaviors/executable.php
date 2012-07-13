<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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
    public function canAdd()
    {
        if($this->getMixer()->getIdentifier()->name == 'session') {
            return true;
        }

        return parent::canAdd();
    }

    public function canEdit()
    {
        if($this->getMixer()->getIdentifier()->name == 'session') {
           return false;
        }

        return parent::canEdit();
    }

    public function canDelete()
    {
        if($this->getMixer()->getIdentifier()->name == 'session')
        {
            //Allow logging out ourselves
            if($this->getModel()->getState()->id == JFactory::getSession()->getId()) {
                return true;
            }

            //Only administrator can logout other users
            if(JFactory::getUser()->gid > 24) {
                return true;
            }

            return false;
        }

        return parent::canDelete();
    }
}