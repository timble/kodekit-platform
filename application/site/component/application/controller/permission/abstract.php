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
 * Controller Permission Abstract Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
abstract class ApplicationControllerPermissionAbstract extends Library\ControllerPermissionAbstract
{
    /**
     * Authorize handler for render actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canRender()
    {
        if(parent::canRender())
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
        }

        return false;
    }

    /**
     * Authorize handler for add actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canAdd()
    {
        if(parent::canAdd() && $this->getUser()->getRole() > 18) {
            return true;
        }

        return false;
    }

    /**
     * Authorize handler for edit actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canEdit()
    {
        if(parent::canEdit() && $this->getUser()->getRole() > 19) {
            return true;
        }

        return false;
    }

    /**
     * Authorize handler for delete actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canDelete()
    {
        if(parent::canDelete() && $this->getUser()->getRole() > 20) {
            return true;
        }

        return false;
    }
}