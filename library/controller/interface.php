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
 * Controller Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
 */
interface ControllerInterface
{
    /**
     * Execute an action by triggering a method in the derived class.
     *
     * @param   string     $action  The action to execute
     * @param   ControllerContext $context A controller context object
     * @throws  ControllerException If the action method doesn't exist
     * @return  mixed|false The value returned by the called method, false in error case.
     */
    public function execute($action, ControllerContextInterface $context);

    /**
     * Get the controller context
     *
     * @return  ControllerContextInterface
     */
    public function getContext();

    /**
     * Gets the available actions in the controller.
     *
     * @return  array Array[i] of action names.
     */
    public function getActions();

    /**
     * Set the request object
     *
     * @param ControllerRequestInterface $request A request object
     * @return ControllerAbstract
     */
    public function setRequest(ControllerRequestInterface $request);

    /**
     * Get the request object
     *
     * @return ControllerRequestInterface
     */
    public function getRequest();

    /**
     * Set the response object
     *
     * @param ControllerResponseInterface $request A request object
     * @return ControllerAbstract
     */
    public function setResponse(ControllerResponseInterface $response);

    /**
     * Get the response object
     *
     * @return ControllerResponseInterface
     */
    public function getResponse();

    /**
     * Set the user object
     *
     * @param UserInterface $user A request object
     * @return UserInterface
     */
    public function setUser(UserInterface $user);

    /**
     * Get the user object
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Has the controller been dispatched
     *
     * @return  boolean	Returns true if the controller has been dispatched
     */
    public function isDispatched();
}