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
 * Controller Response Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
 */
interface ControllerResponseInterface extends HttpResponseInterface
{
    /**
     * Flash messages types
     *
     * Note : Messages types
     */
    const FLASH_SUCCESS = 'success';
    const FLASH_NOTICE  = 'notice';
    const FLASH_WARNING = 'warning';
    const FLASH_ERROR   = 'error';

    /**
     * Set the request object
     *
     * @param ControllerRequestInterface $request A request object
     * @return ControllerResponse
     */
    public function setRequest(ControllerRequestInterface $request);

    /**
     * Get the request object
     *
     * @return ControllerRequestInterface
     */
    public function getRequest();

    /**
     * Set the user object
     *
     * @param UserInterface $user A request object
     * @return ControllerResponse
     */
    public function setUser(UserInterface $user);

    /**
     * Get the user object
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Set a redirect location
     *
     * Method will use a 303 See other status by default if no other redirect status code is set yet. The flash
     * message is a self-expiring messages that will only live for exactly one request before being purged.
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3
     *
     * @param  string   $location   The redirect location
     * @param  string   $message    The flash message
     * @param  string   $type       The flash message category type. Default is 'success'.
     * @throws \InvalidArgumentException If the location is empty
     * @throws \UnexpectedValueException If the location is not a string, or cannot be cast to a string
     * @return DispatcherResponse
     */
    public function setRedirect($location, $message, $type = self::FLASH_SUCCESS);

    /**
     * Add a response message
     *
     * Response messages are self-expiring messages that are meant to live for exactly one request before being
     * purged. The purge behavior depends on the request method.
     *
     * @param  string   $message   The flash message
     * @param  string   $type      Message category type. Default is 'success'.
     * @return ControllerResponse
     */
    public function addMessage($message, $type = self::FLASH_SUCCESS);

    /**
     * Set the response messages
     *
     * @param array $messages
     * @return $this
     */
    public function setMessages($messages);

    /**
     * Get the response messages
     *
     * @return array
     */
    public function getMessages();
}