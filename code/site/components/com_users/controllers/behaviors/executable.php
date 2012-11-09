<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * User Controller Executable Behavior
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerBehaviorExecutable extends ComDefaultControllerBehaviorExecutable
{
    public function canRead()
    {
    	$parameters = $this->getService('application.components')->users->params;

        if($parameters->get('allowUserRegistration') == '0')
        {
	        $view = $this->getView();
	    	if ($view->getName() === 'user' && $view->getLayout() === 'register') {
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
        if($this->getMixer()->getIdentifier()->name != 'session')
        {
            $request = $this->getRequest();

            if($request->id == 0 || $request->id != JFactory::getUser()->id) {
                return false;
            }

            $result = !JFactory::getUser()->guest;
            return $result;
        }
        else return false;
    }

    public function canAdd()
    {
        if($this->getMixer()->getIdentifier()->name != 'session')
        {
            $parameters = $this->getService('application.components')->users->params;

            if($parameters->get('allowUserRegistration') == '0') {
                return false;
            }

            return true;
        }
        else return true;
    }

    public function canDelete()
    {
        if($this->getMixer()->getIdentifier()->name == 'session')
        {
            // Allow logging out ourselves
            if($this->getModel()->getState()->id == JFactory::getSession()->getId()) {
                return true;
            }

            // Only administrator can logout other users
            if(JFactory::getUser()->role_id > 24) {
                return true;
            }

            return false;
        }

        return parent::canDelete();
    }
}