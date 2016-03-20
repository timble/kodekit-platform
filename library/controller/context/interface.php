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
 * Controller Context Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
 */
interface ControllerContextInterface extends CommandInterface
{
    /**
     * Get the request object
     *
     * @return ControllerRequestInterface
     */
    public function getRequest();

    /**
     * Get the response object
     *
     * @return ControllerResponseInterface
     */
    public function getResponse();

    /**
     * Get the user object
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Get the controller action
     *
     * @return string
     */
    public function getAction();
}