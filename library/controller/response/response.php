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
 * Controller Response
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
 */
class ControllerResponse extends HttpResponse implements ControllerResponseInterface
{
    /**
     * Request object
     *
     * @var	string|object
     */
    protected $_request;

    /**
     * User object
     *
     * @var	string|object
     */
    protected $_user;

    /**
     * The response messages
     *
     * @var	array
     */
    protected $_messages;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the request
        $this->setRequest($config->request);

        //Set the user
        $this->setUser($config->user);

        //Set the messages
        $this->_messages = array();
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config    An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'request' => null,
            'user'    => null
        ));

        parent::_initialize($config);
    }

    /**
     * Set the request object
     *
     * @param ControllerRequestInterface $request A request object
     * @return ControllerResponse
     */
    public function setRequest(ControllerRequestInterface $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Get the request object
     *
     * @return ControllerRequestInterface
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Set the user object
     *
     * @param UserInterface $user A request object
     * @return ControllerResponse
     */
    public function setUser(UserInterface $user)
    {
        $this->_user = $user;
        return $this;
    }

    /**
     * Get the user object
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * Sets a redirect
     *
     * Redirect to a URL externally. Method performs a 303 (see other) redirect if no other redirect status code is set
     * in the response. The flash message is a self-expiring messages that will only live for exactly one request before
     * being purged.
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
    public function setRedirect($location, $message = '', $type = self::FLASH_SUCCESS)
    {
        if (!empty($location))
        {
            if (!is_string($location) && !is_numeric($location) && !is_callable(array($location, '__toString')))
            {
                throw new \UnexpectedValueException(
                    'The Response location must be a string or object implementing __toString(), "'.gettype($location).'" given.'
                );
            }
        }
        else throw new \InvalidArgumentException('Cannot redirect to an empty URL.');

        //Add the message
        if(!empty($message)) {
           $this->addMessage($message, $type);
        }

        //Force the status code to 303 if no redirect status code is set.
        if(!$this->isRedirect()) {
            $this->setStatus(self::SEE_OTHER);
        }

        //Set the location header.
        $this->getHeaders()->set('Location', (string) $location);

        return $this;
    }

    /**
     * Add a response message
     *
     * Flash messages are self-expiring messages that are meant to live for exactly one request. They can be used
     * across redirects, or flushed at the end of the request.
     *
     * @param  string   $message   The flash message
     * @param  string   $type      Message category type. Default is 'success'.
     * @return ControllerResponse
     */
    public function addMessage($message, $type = self::FLASH_SUCCESS)
    {
        if (!is_string($message) && !is_callable(array($message, '__toString')))
        {
            throw new \UnexpectedValueException(
                'The flash message must be a string or object implementing __toString(), "'.gettype($message).'" given.'
            );
        }

        if(!isset($this->_messages[$type])) {
            $this->_messages[$type] = array();
        }

        $this->_messages[$type][] = $message;
        return $this;
    }

    /**
     * Get the response messages
     *
     * @param  boolean $flush   If TRUE flush the messages. Default is TRUE.
     * @return array
     */
    public function getMessages($flush = true)
    {
        $result = $this->_messages;

        if($flush) {
            $this->_messages = array();
        }

        return $result;
    }

    /**
     * Set the response messages
     *
     * @param array $messages
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->_messages = $messages;
        return $this;
    }

    /**
     * Implement a virtual 'headers' class property to return their respective objects.
     *
     * @param   string $name  The property name.
     * @return  mixed The property value.
     */
    public function __get($name)
    {
        $result = null;
        if($name == 'headers') {
            $result = $this->getHeaders();
        }

        return $result;
    }

    /**
     * Deep clone of this instance
     *
     * @return void
     */
    public function __clone()
    {
        parent::__clone();

        $this->_request = clone $this->_request;
        $this->_user    = clone $this->_user;
    }
}