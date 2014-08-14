<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Dispatcher Permission
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
abstract class DispatcherPermissionAbstract extends ObjectMixinAbstract implements DispatcherPermissionInterface
{
    /**
     * Permission handler for forward actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canForward()
    {
        return true;
    }

    /**
     * Permission handler for dispatch actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canDispatch()
    {
        return false;
    }

    /**
     * Permission handler for fail actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canFail()
    {
        return true;
    }

    /**
     * Permission handler for redirect actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canRedirect()
    {
        return true;
    }

    /**
     * Permission handler for send actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canSend()
    {
        return true;
    }
}