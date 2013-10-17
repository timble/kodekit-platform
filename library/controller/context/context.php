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
 * Controller Context
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
class ControllerContext extends Command implements ControllerContextInterface
{
    /**
     * Get the request object
     *
     * @return ControllerRequestInterface
     */
    public function getRequest()
    {
        return $this->get('request');
    }

    /**
     * Set the request object
     *
     * @param ControllerRequestInterface $request
     * @return ControllerContext
     */
    public function setRequest(ControllerRequestInterface $request)
    {
        $this->set('request', $request);
        return $this;
    }

    /**
     * Get the response object
     *
     * @return ControllerResponseInterface
     */
    public function getResponse()
    {
        return $this->get('response');
    }

    /**
     * Set the response object
     *
     * @param ControllerResponseInterface $response
     * @return ControllerContext
     */
    public function setResponse(ControllerResponseInterface $response)
    {
        $this->set('response', $response);
        return $this;
    }

    /**
     * Get the user object
     *
     * @return ControllerUserInterface
     */
    public function getUser()
    {
        return $this->get('user');
    }

    /**
     * Set the user object
     *
     * @param ControllerResponseInterface $response
     * @return ControllerContext
     */
    public function setUser(ControllerUserInterface $user)
    {
        $this->set('user', $user);
        return $this;
    }
}