<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * User Controller Executable Behavior
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class UsersControllerPermissionUser extends ApplicationControllerPermissionDefault
{
    public function canRead()
    {
        $result = true;

        $layout       = $this->getView()->getLayout();
        $row        = $this->getModel()->getRow();
        $parameters = $this->getObject('application.extensions')->users->params;

        if (!$row->isNew() && $layout != 'password') {
            $result = $this->canEdit();
        } elseif ($parameters->get('allowUserRegistration') == '0' && $layout == 'form') {
            // Restrict registrations if these are disabled.
            $result = false;
        }

        return $result;
    }
    
    public function canBrowse()
    {
        return false;
    }

    public function canEdit()
    {
        $result = false;

        $row  = $this->getModel()->getRow();
        $user = $this->getUser();

        if ($row->id == $user->getId() || $this->canDelete()) {
            $result = true;
        }

        return $result;
    }

    public function canAdd()
    {
        $parameters = $this->getObject('application.extensions')->users->params;

        if($parameters->get('allowUserRegistration') == '0') {
            return false;
        }

        return true;
    }
}