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
 * Abstract Dispatcher Authenticator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 */
abstract class DispatcherAuthenticatorAbstract extends Object implements DispatcherAuthenticatorInterface
{
    /**
     * The authentication scheme
     *
     * @var string
     */
    protected $_scheme;

    /**
     * The authentication realm
     *
     * @var string
     */
    protected $_realm;

    /**
     * The authenticator priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Login the user if authenticated successfully
     *
     * @var boolean
     */
    protected $_login_user;

    /**
     * Constructor.
     *
     * @param  ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_priority = $config->priority;

        $this->_scheme     = $config->scheme;
        $this->_realm      = $config->realm;
        $this->_login_user = $config->login_user;
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
            'priority'   => self::PRIORITY_NORMAL,
            'scheme'     => $this->getIdentifier()->name,
            'realm'      => (string) ltrim($this->getObject('request')->getBaseUrl()->getPath(), '/') ?: '',
            'login_user' => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the authentication scheme
     *
     * @link http://tools.ietf.org/html/rfc7235#section-4.1
     *
     * @return string The authentication scheme
     */
    public function getScheme()
    {
        return $this->_scheme;
    }

    /**
     * Get the authentication realm
     *
     * @link http://tools.ietf.org/html/rfc7235#section-2.2
     *
     * @return string The authentication realm
     */
    public function getRealm()
    {
        return $this->_realm;
    }

    /**
     * Get the priority of the filter
     *
     * @return  integer The priority level
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * Challenge the response
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return bool Returns TRUE if the response could be signed, FALSE otherwise.
     */
    public function challengeResponse(DispatcherContextInterface $context)
    {
        $response = $context->getResponse();

        //The response MUST include a WWW-Authenticate header field.
        if($response->getStatusCode() == HttpResponse::UNAUTHORIZED) {
            $response->headers->set('Www-Authenticate', ucfirst($this->getScheme()).' realm="'.$this->getRealm().'"', false);
        }
    }

    /**
     * Log the user in
     *
     * @param mixed  $user A user key or name, an array of user data or a UserInterface object. Default NULL
     * @param array  $properties Optional user properties
     * @return bool
     */
    public function loginUser($user = null, $properties = array())
    {
        if($this->_login_user && $user)
        {
            //Get the user data
            if(!is_array($user))
            {
                if($user instanceof UserInterface) {
                    $user = $user->toArray();
                } else {
                    $user = $this->getObject('user.provider')->getUser($user)->toArray();
                }
            }

            //Set the user properties
            $properties = array_merge($properties, (array) $user);

            $this->getObject('user')->setProperties($properties);
            $this->getObject('user')->setAuthentic();

            return true;
        }

        return false;
    }
}