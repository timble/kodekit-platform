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
 * Controller Response Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
interface ControllerResponseInterface extends HttpResponseInterface
{
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
     * @param ControllerUserInterface $user A request object
     * @return ControllerResponse
     */
    public function setUser(ControllerUserInterface $user);

    /**
     * Get the user object
     *
     * @return ControllerUserInterface
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
     * Get the response messages
     *
     * @return array
     */
    public function getMessages();
}