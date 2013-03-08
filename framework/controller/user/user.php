<?php
/**
 * @package		Koowa_Controller
 * @subpackage  User
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Controller User Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  User
 */
class ControllerUser extends User implements ControllerUserInterface
{
    /**
     * Get a user attribute
     *
     * Implement a virtual 'session' class property to return the session object.
     *
     * @param   string $name  The attribute name.
     * @return  string $value The attribute value.
     */
    public function __get($name)
    {
        if($name = 'session') {
            return $this->getSession();
        }

        return parent::__get($name);
    }
}