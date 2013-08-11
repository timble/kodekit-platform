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
 * Controller Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
interface ControllerInterface
{
    /**
     * Execute an action by triggering a method in the derived class.
     *
     * @param   string     $action  The action to execute
     * @param   object	   $context  A command context object
     * @throws  ControllerException If the action method doesn't exist
     * @return  mixed|false The value returned by the called method, false in error case.
     */
    public function execute($action, CommandContext $context);

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
     * @param ControllerUserInterface $user A request object
     * @return ControllerUser
     */
    public function setUser(ControllerUserInterface $user);

    /**
     * Get the user object
     *
     * @return ControllerUserInterface
     */
    public function getUser();

    /**
     * Register (map) an action to a method in the class.
     *
     * @param   string  $alias  The action.
     * @param   string  $action The name of the method in the derived class to perform for this action.
     * @return  ControllerAbstract
     */
    public function registerActionAlias($alias, $action);

    /**
     * Has the controller been dispatched
     *
     * @return  boolean	Returns true if the controller has been dispatched
     */
    public function isDispatched();
}