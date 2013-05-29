<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Controller Permission Default Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ApplicationControllerPermissionDefault extends Library\ControllerPermissionAbstract
{
    /**
     * Generic authorize handler for controller render actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canRender()
    {
        $application = $this->getObject('application');
        $user        = $this->getUser();
        $request     = $this->getRequest();

        if(!($application->getCfg('offline') && !$user->isAuthentic()))
        {
            $page = $request->query->get('Itemid', 'int');

            if($this->isDispatched())
            {
                if($this->getObject('application.pages')->isAuthorized($page, $user)) {
                    return true;
                }
            }
            else return true;
        }

        return false;
    }

    /**
     * Generic authorize handler for controller add actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canAdd()
    {
        return $this->getUser()->getRole() > 18;
    }

    /**
     * Generic authorize handler for controller edit actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canEdit()
    {
        return $this->getUser()->getRole() > 19;
    }

    /**
     * Generic authorize handler for controller delete actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canDelete()
    {
        return $this->getUser()->getRole() > 20;
    }
}