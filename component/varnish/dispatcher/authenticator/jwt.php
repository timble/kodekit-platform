<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

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
     * @param   Library\DispatcherContextInterface $context	A dispatcher context object
     * @throws  Library\ControllerExceptionRequestNotAuthenticated If a JWT token cannot be found in the request.
     * @return  boolean Returns TRUE if the authentication explicitly succeeded.
     */
    public function authenticateRequest(Library\DispatcherContextInterface $context)
    {
        if(!$this->getToken()){
            throw new Library\ControllerExceptionRequestNotAuthenticated('Token Not Found');
        }

        return parent::authenticateRequest($context);
    }
}