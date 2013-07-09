<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Dispatcher Response Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Response
 */
interface DispatcherResponseInterface extends ControllerResponseInterface, DispatcherResponseTransportInterface
{
    /**
     * Get the transport strategy
     *
     * @throws	\UnexpectedValueException	If the transport object doesn't implement the
     *                                      DispatcherResponseTransportInterface
     * @return	DispatcherResponseTransportInterface
     */
    public function getTransport();

    /**
     * Method to set a transport strategy
     *
     * @param	mixed	$transport An object that implements ObjectInterface, ObjectIdentifier object
     * 					           or valid identifier string
     * @return	DispatcherResponse
     */
    public function setTransport($transport);
}