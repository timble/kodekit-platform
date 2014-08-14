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
 * Jwt Dispatcher Authenticator
 *
 * Token based authentication using the JSON Web Token standard.
 *
 * Clients should authenticate by passing the token key in the "Authorization" HTTP header, prepended with the string
 * "JWT ". For example: Authorization: JWT [header.payload.signature]
 *
 * Token Requirements :
 *
 * - The token SHOULD be signed
 * - The token SHOULD contain a expire time 'exp' claim.
 * - The token SHOULD contain a issue time 'iat' claim.
 * - The subject 'sub' claim of the token SHOULD contain the user key or user name for the user to be authenticated.
 *
 * A token MAY contain and additional 'user' claim which contains a JSON hash of user field key and values to set on
 * the user.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherAuthenticatorJwt extends DispatcherAuthenticatorAbstract
{
    /**
     * The JWT token
     *
     * @var HttpToken
     */
    private $__token;

    /**
     * The secret
     *
     * The secret to be used to verify the HMAC signature bytes of the JWT token
     *
     * @var mixed
     */
    protected $_secret;

    /**
     * The max age
     *
     * The maximum token age in seconds for the token to be considered valid.
     *
     * @var integer
     */
    protected $_max_age;

    /**
     * The max age
     *
     * Check if the user exists
     *
     * @var boolean
     */
    protected $_check_user;

    /**
     * Constructor.
     *
     * @param   ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_secret     = $config->secret;
        $this->_max_age    = $config->max_age;
        $this->_check_user = $config->check_user;

        $this->addCommandCallback('before.dispatch', 'authenticateRequest');

    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'secret'     => '',
            'max_age'    => 900,
            'check_user' => true
        ));

        parent::_initialize($config);
    }

    /**
     * Return the JWT authorisation token
     *
     * @return HttpToken  The authorisation token or NULL if no token could be found
     */
    public function getAuthToken()
    {
        if(!isset($this->__token))
        {
            $token   = false;
            $request = $this->getObject('request');

            if($request->headers->has('Authorization'))
            {
                $header = $request->headers->get('Authorization');

                if(stripos($header, 'jwt') === 0) {
                    $token = substr($header , 4);
                }
            }

            if($request->isSafe())
            {
                if($request->query->has('auth_token')) {
                    $token = $request->query->get('auth_token', 'url');
                }
            }
            else
            {
                if($request->data->has('auth_token')) {
                    $token = $request->data->get('auth_token', 'url');
                }
            }

            if($token) {
                $token = $this->getObject('lib:http.token')->fromString($token);
            }

            $this->__token = $token;
        }

        return $this->__token;
    }

    /**
     * Authenticate using a JWT token
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @throws ControllerExceptionRequestNotAuthenticated
     * @return  boolean Returns FALSE if the check failed. Otherwise TRUE.
     */
    public function authenticateRequest(DispatcherContextInterface $context)
    {
        if(!$context->user->isAuthentic() && $token = $this->getAuthToken())
        {
            if($token->verify($this->_secret))
            {
                $username = $token->getSubject();
                $data     = (array) $token->getClaim('user');

                //Ensure the token is not expired
                if(!$token->getExpireTime() || $token->isExpired()) {
                    throw new ControllerExceptionRequestNotAuthenticated('Token Expired');
                }

                //Ensure the token is not too old
                if(!$token->getIssueTime() || $token->getAge() > $this->_max_age) {
                    throw new ControllerExceptionRequestNotAuthenticated('Token Expired');
                }

                //Ensure we have a username
                if(empty($username)) {
                    throw new ControllerExceptionRequestNotAuthenticated('Invalid Username');
                }

                //Ensure the user has an account already
                if($this->_check_user && $this->getObject('user.provider')->load($username)->getId() == 0) {
                    throw new ControllerExceptionRequestNotAuthenticated('User Not Found');
                }

                return $this->_loginUser($username, $data);
            }
            else throw new ControllerExceptionRequestNotAuthenticated('Invalid Token');
        }

        return true;
    }

    /**
     * Log the user in
     *
     * @param string $username  A user key or name
     * @param array  $data      Optional user data
     *
     * @return bool
     */
    protected function _loginUser($username, $data = array())
    {
        //Set user data in context
        $data = $this->getObject('user.provider')->load($username)->toArray();
        $data['authentic'] = true;

        $this->getObject('user')->setData($data);

        return true;
    }
}