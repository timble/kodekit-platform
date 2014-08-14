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
 * Controller Context
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
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
        return ObjectConfig::get('request');
    }

    /**
     * Set the request object
     *
     * @param ControllerRequestInterface $request
     * @return ControllerContext
     */
    public function setRequest(ControllerRequestInterface $request)
    {
        return ObjectConfig::set('request', $request);
    }

    /**
     * Get the response object
     *
     * @return ControllerResponseInterface
     */
    public function getResponse()
    {
        return ObjectConfig::get('response');
    }

    /**
     * Set the response object
     *
     * @param ControllerResponseInterface $response
     * @return ControllerContext
     */
    public function setResponse(ControllerResponseInterface $response)
    {
        return ObjectConfig::set('response', $response);
    }

    /**
     * Get the user object
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return ObjectConfig::get('user');
    }

    /**
     * Set the user object
     *
     * @param UserInterface $response
     * @return ControllerContext
     */
    public function setUser(UserInterface $user)
    {
        return ObjectConfig::set('user', $user);
    }

    /**
     * Get the controller action
     *
     * @return string
     */
    public function getAction()
    {
        return ObjectConfig::get('action');
    }

    /**
     * Set the controller action
     *
     * @param string $action
     * @return ControllerContext
     */
    public function setAction($action)
    {
        return ObjectConfig::set('action', $action);
    }
}