<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Dispatcher Authenticator Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
interface DispatcherAuthenticatorInterface
{
    /**
     * Priority levels
     */
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH    = 2;
    const PRIORITY_NORMAL  = 3;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 5;

    /**
     * Get the authentication scheme
     *
     * @link http://tools.ietf.org/html/rfc7235#section-4.1
     *
     * @return string The authentication scheme
     */
    public function getScheme();

    /**
     * Get the authentication realm
     *
     * @link http://tools.ietf.org/html/rfc7235#section-2.2
     *
     * @return string The authentication realm
     */
    public function getRealm();

    /**
     * Get the priority of the authenticator
     *
     * @return  integer The priority level
     */
    public function getPriority();

    /**
     * Authenticate the request
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return  boolean Returns TRUE if the authentication explicitly succeeded.
     */
    public function authenticateRequest(DispatcherContextInterface $context);

    /**
     * Challenge the response
     *
     * @link http://tools.ietf.org/html/rfc7235#section-2.1
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return void
     */
    public function challengeResponse(DispatcherContextInterface $context);

    /**
     * Log the user in
     *
     * @param mixed  $user A user key or name, an array of user data or a UserInterface object. Default NULL
     * @param array  $data Optional user data
     * @return bool
     */
    public function loginUser($user = null, $data = array());
}