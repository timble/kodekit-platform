<?php
/**
 * @version		$Id$
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
     * Has the controller been dispatched
     * 
     * @return  boolean	Returns true if the controller has been dispatched
     */
    public function isDispatched();

    /**
     * Execute an action by triggering a method in the derived class.
     *
     * @param   string      The action to execute
     * @param   object		A command context object
     * @return  mixed|false The value returned by the called method, false in error case.
     * @throws  KControllerException
     */
    public function execute($action, KCommandContext $context);

    /**
     * Gets the available actions in the controller.
     *
     * @return  array Array[i] of action names.
     */
    public function getActions();
    
	/**
	 * Get the request information
	 *
	 * @return KConfig	A KConfig object with request information
	 */
	public function getRequest();

	/**
	 * Set the request information
	 *
	 * @param array	An associative array of request information
	 * @return KControllerAbstract
	 */
	public function setRequest(array $request);

    /**
     * Register (map) an action to a method in the class.
     *
     * @param   string  The action.
     * @param   string  The name of the method in the derived class to perform
     *                  for this action.
     * @return  KControllerAbstract
     */
    public function registerActionAlias($alias, $action);
}