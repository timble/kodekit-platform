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
     * Check if the user exists
     *
     * @var boolean
     */
    protected $_check_user;

    /**
     * Check if the token is too old
     *
     * @var boolean
     */
    protected $_check_age;

    /**
     * Check if the token is expired
     *
     * @var boolean
     */
    protected $_check_expire;

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

        $this->_check_user   = $config->check_user;
        $this->_check_age    = $config->check_age;
        $this->_check_expire = $config->check_expire;
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
            'priority'   => self::PRIORITY_HIGH,
            'secret'     => '',
            'max_age'    => 900,
            'check_user'   => true,
            'check_age'    => true,
            'check_expire' => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Return the JWT authorisation token
     *
     * @return HttpToken  The authorisation token or NULL if no token could be found
     */
    public function getSecret()
    {
        return $this->_secret;
    }

    /**
     * Return the JWT authorisation token
     *
     * @return HttpToken  The authorisation token or NULL if no token could be found
     */
    public function getToken()
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
     * Create a new signed JWT authorisation token
     *
     * If not user passed, the context user object will be used.
     *
     * @param UserInterface $user The user object. Default NULL
     * @return string  The signed authorisation token
     */
    public function createToken(UserInterface $user = null)
    {
        if(!$user) {
            $user = $this->getObject('user');
        }

        $token = $this->getObject('lib:http.token')
            ->setSubject($user->getId())
            ->sign($this->getSecret());

        return $token;
    }

    /**
     * Authenticate using a JWT token
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @throws ControllerExceptionRequestNotAuthenticated
     * @return  boolean Returns TRUE if the authentication explicitly succeeded.
     */
    public function authenticateRequest(DispatcherContextInterface $context)
    {
        if($token = $this->getToken())
        {
            if($token->verify($this->getSecret()))
            {
                $user = $token->getSubject();
                $data = (array) $token->getClaim('user');

                //Ensure the token is not expired
                if($this->_check_expire)
                {
                    if(!$token->getExpireTime() || $token->isExpired()) {
                        throw new ControllerExceptionRequestNotAuthenticated('Token Expired');
                    }
                }

                //Ensure the token is not too old
                if($this->_check_age)
                {
                    if (!$token->getIssueTime() || $token->getAge() > $this->_max_age) {
                        throw new ControllerExceptionRequestNotAuthenticated('Token Expired');
                    }
                }

                //Ensure the user exists
                if($this->_check_user)
                {
                    //Ensure we have a username
                    if(empty($user)) {
                        throw new ControllerExceptionRequestNotAuthenticated('Invalid User');
                    }

                    if($this->getObject('user.provider')->getUser($user)->getId() == 0) {
                        throw new ControllerExceptionRequestNotAuthenticated('User Not Found');
                    }
                }

                //Login the user
                return $this->loginUser($user, $data);
            }
            else throw new ControllerExceptionRequestNotAuthenticated('Invalid Token');
        }
    }
}