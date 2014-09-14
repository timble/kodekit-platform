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
 * Abstract Dispatcher Authenticator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
abstract class DispatcherAuthenticatorAbstract extends BehaviorAbstract implements DispatcherAuthenticatorInterface
{
    /**
     * Authenticate the request
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return bool Returns TRUE if the request could be authenticated, FALSE otherwise.
     */
    public function authenticateRequest(DispatcherContextInterface $context)
    {
        return false;
    }

    /**
     * Sign the response
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return bool Returns TRUE if the response could be signed, FALSE otherwise.
     */
    public function signResponse(DispatcherContextInterface $context)
    {
        return false;
    }

    /**
     * Get the methods that are available for mixin based
     *
     * @param  array           $exclude     An array of public methods to be exclude
     * @return array An array of methods
     */
    public function getMixableMethods($exclude = array())
    {
        $exclude = array_merge($exclude, array('authenticateRequest', 'signResponse'));
        return parent::getMixableMethods($exclude);
    }
}