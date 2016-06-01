<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-varnish for the canonical source repository
 */

namespace Kodekit\Component\Varnish;

use Kodekit\Library;

/**
 * Dispatcher Jwt Authenticator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Varnish
 */
class DispatcherAuthenticatorJwt extends Library\DispatcherAuthenticatorJwt
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => self::PRIORITY_HIGHEST,
            'secret'     => '',
            'check_user'   => false,
            'check_expire' => false,
            'check_age'    => false,
        ));

        parent::_initialize($config);
    }

    /**
     * Authenticate using a JWT token
     *
     * A JWt token is required.
     *
     * @param   Library\DispatcherContext $context  A dispatcher context object
     * @throws  Library\ControllerExceptionRequestNotAuthenticated If a JWT token cannot be found in the request.
     * @return  boolean Returns TRUE if the authentication explicitly succeeded.
     */
    public function authenticateRequest(Library\DispatcherContext $context)
    {
        if(!$this->getToken()){
            throw new Library\ControllerExceptionRequestNotAuthenticated('Token Not Found');
        }

        return parent::authenticateRequest($context);
    }
}