<?php
/**
 * @package		Koowa_Controller
 * @subpackage	Permission
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract Controller Permission Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage	Permission
 */
interface ControllerPermissionInterface
{
    /**
     * Check if an action can be executed
     *
     * @param   string  Action name
     * @return  boolean True if the action can be executed, otherwise FALSE.
     */
    public function isPermitted($action);
}