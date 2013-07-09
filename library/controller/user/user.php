<?php
/**
 * @package		Koowa_Controller
 * @subpackage  User
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

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
     * Set the request object
     *
     * @param ControllerRequestInterface $request A request object
     * @return ControllerUser
     */
    public function setRequest(ControllerRequestInterface $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Get the request object
     *
     * @return ControllerRequestInterface
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Get a user attribute
     *
     * Implements a virtual 'session' class property to return the session object.
     *
     * @param   string $name  The attribute name.
     * @return  string $value The attribute value.
     */
    public function __get($name)
    {
        if($name == 'session') {
            return $this->getSession();
        }

        return parent::__get($name);
    }
}