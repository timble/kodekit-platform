<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Dispatcher Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 */
interface KDispatcherInterface extends KControllerInterface
{
    /**
     * Method to get a controller object
     *
     * @throws	\UnexpectedValueException	If the controller doesn't implement the KControllerInterface
     * @return	KDispatcherInterface
     */
    public function getController();

    /**
     * Method to set a controller object attached to the dispatcher
     *
     * @param	mixed	$controller An object that implements KControllerInterface, KServiceIdentifier object
     * 					            or valid identifier string
     * @return	KDispatcherInterface
     */
    public function setController($controller, $config = array());
}