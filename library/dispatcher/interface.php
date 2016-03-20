<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Dispatcher Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 */
interface DispatcherInterface extends ControllerInterface
{
    /**
     * Method to get a controller object
     *
     * @throws	\UnexpectedValueException	If the controller doesn't implement the ControllerInterface
     * @return	DispatcherInterface
     */
    public function getController();

    /**
     * Method to set a controller object attached to the dispatcher
     *
     * @param	mixed	$controller An object that implements ControllerInterface, ObjectIdentifier object
     * 					            or valid identifier string
     * @param  array  $config  An optional associative array of configuration options
     * @return	DispatcherInterface
     */
    public function setController($controller, $config = array());

    /**
     * Method to get a controller action to be executed
     *
     * @return	string
     */
    public function getControllerAction();

    /**
     * Method to set the controller action to be executed
     *
     * @return	DispatcherInterface
     */
    public function setControllerAction($action);
}