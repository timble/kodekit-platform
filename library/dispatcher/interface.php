<?php
/**
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Dispatcher Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
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
     * @return	DispatcherInterface
     */
    public function setController($controller, $config = array());
}