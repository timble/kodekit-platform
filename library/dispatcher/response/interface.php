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
 * Dispatcher Response Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 */
interface DispatcherResponseInterface extends ControllerResponseInterface
{
    /**
     * Send the response
     *
     * @return boolean  Returns true if the response has been send, otherwise FALSE
     */
    public function send();

    /**
     * Sets the response content using a stream
     *
     * @param FilesystemStreamInterface $stream  The stream object
     * @return HttpMessage
     */
    public function setStream(FilesystemStreamInterface $stream);

    /**
     * Get the stream resource
     *
     * @return FilesystemStreamInterface
     */
    public function getStream();

    /**
     * Get a transport handler by identifier
     *
     * @param   mixed    $transport    An object that implements ObjectInterface, ObjectIdentifier object
     *                                 or valid identifier string
     * @param   array    $config    An optional associative array of configuration settings
     * @return DispatcherResponseInterface
     */
    public function getTransport($transport, $config = array());

    /**
     * Attach a transport handler
     *
     * @param   mixed  $transport An object that implements ObjectInterface, ObjectIdentifier object
     *                            or valid identifier string
     * @param   array $config  An optional associative array of configuration settings
     * @return DispatcherResponseInterface
     */
    public function attachTransport($transport, $config = array());
}