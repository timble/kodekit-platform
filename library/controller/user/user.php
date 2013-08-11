<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Controller User
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
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