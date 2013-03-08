<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Default Controller Executable Behavior
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComBaseControllerPermissionDefault extends Framework\ControllerPermissionDefault
{
    /**
     * Generic authorize handler for controller render actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canRender()
    {
        return $this->getUser()->getRole() > 22;
    }


    /**
     * Generic authorize handler for controller read actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canRead()
    {
        return $this->getUser()->getRole() > 22;
    }

    /**
     * Generic authorize handler for controller browse actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canBrowse()
    {
        return $this->getUser()->getRole() > 22;
    }

    /**
     * Generic authorize handler for controller add actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canAdd()
    {
        return $this->getUser()->getRole() > 22;
    }

  	/**
     * Generic authorize handler for controller edit actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canEdit()
    {
        return $this->getUser()->getRole() > 22;
    }

    /**
     * Generic authorize handler for controller delete actions
     *
     * @return  boolean     Can return both true or false.
     */
    public function canDelete()
    {
        return $this->getUser()->getRole() > 22;
    }
}