<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Controller Executable Behavior
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerPermissionDefault extends KControllerPermissionDefault
{
    /**
     * Generic authorize handler for controller render actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canRender()
    {
        $result = false;

        if(parent::canRender()) {
            $result = $this->getUser()->getRole() > 22;
        }

        return $result;
    }


    /**
     * Generic authorize handler for controller read actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canRead()
    {
        $result = false;

        if(parent::canRead()) {
            $result = $this->getUser()->getRole() > 22;
        }

        return $result;
    }

    /**
     * Generic authorize handler for controller browse actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canBrowse()
    {
        $result = false;

        if(parent::canBrowse()) {
            $result = $this->getUser()->getRole() > 22;
        }

        return $result;
    }

    /**
     * Generic authorize handler for controller add actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canAdd()
    {
        $result = false;

        if(parent::canAdd()) {
            $result = $this->getUser()->getRole() > 22;
        }

        return $result;
    }

  	/**
     * Generic authorize handler for controller edit actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canEdit()
    {
        $result = false;

        if(parent::canEdit()) {
            $result = $this->getUser()->getRole() > 22;
        }

        return $result;
    }

    /**
     * Generic authorize handler for controller delete actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canDelete()
    {
        $result = false;

        if(parent::canDelete()) {
            $result = $this->getUser()->getRole() > 22;
        }

        return $result;
    }
}