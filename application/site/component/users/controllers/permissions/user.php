<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * User Controller Executable Behavior
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerPermissionUser extends ComDefaultControllerPermissionDefault
{
    public function canRead()
    {
    	$parameters = $this->getService('application.components')->users->params;

        if($parameters->get('allowUserRegistration') == '0')
        {
	        $view = $this->getView();
	    	if ($view->getLayout() === 'register') {
	    		return false;
	    	}
        }

        return true;
    }
    
    public function canBrowse()
    {
        return false;
    }

    public function canEdit()
    {
        $id = $this->getRequest()->query->get('id', 'int');

        if( $id == 0 || $id != $this->getUser()->getId()) {
            return false;
        }

        $result = $this->getUser()->isAuthentic();
        return $result;
    }

    public function canAdd()
    {
        $parameters = $this->getService('application.components')->users->params;

        if($parameters->get('allowUserRegistration') == '0') {
            return false;
        }

        return true;
    }
}