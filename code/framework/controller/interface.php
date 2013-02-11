<?php
/**
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Controller Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @uses        KMixinClass
 * @uses        KCommandChain
 */
interface KControllerInterface
{
    /**
     * Execute an action by triggering a method in the derived class.
     *
     * @param   string     $action  The action to execute
     * @param   object	   $context  A command context object
     * @throws  KControllerException If the action method doesn't exist
     * @return  mixed|false The value returned by the called method, false in error case.
     */
    public function execute($action, KCommandContext $context);

    /**
     * Gets the available actions in the controller.
     *
     * @return  array Array[i] of action names.
     */
    public function getActions();

    /**
     * Set the request object
     *
     * @param KControllerRequestInterface $request A request object
     * @return KControllerAbstract
     */
    public function setRequest(KControllerRequestInterface $request);

    /**
     * Get the request object
     *
     * @return KControllerRequestInterface
     */
    public function getRequest();

    /**
     * Set the response object
     *
     * @param KControllerResponseInterface $request A request object
     * @return KControllerAbstract
     */
    public function setResponse(KControllerResponseInterface $response);

    /**
     * Get the response object
     *
     * @return KControllerResponseInterface
     */
    public function getResponse();

    /**
     * Set the user object
     *
     * @param KControllerUserInterface $user A request object
     * @return KControllerUser
     */
    public function setUser(KControllerUserInterface $user);

    /**
     * Get the user object
     *
     * @return KControllerUserInterface
     */
    public function getUser();

    /**
     * Register (map) an action to a method in the class.
     *
     * @param   string  The action.
     * @param   string  The name of the method in the derived class to perform
     *                  for this action.
     * @return  KControllerAbstract
     */
    public function registerActionAlias($alias, $action);

    /**
     * Has the controller been dispatched
     *
     * @return  boolean	Returns true if the controller has been dispatched
     */
    public function isDispatched();
}