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
 * Dispatcher Response Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
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