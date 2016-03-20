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
 * Controller Request
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
 */
class ControllerRequest extends HttpRequest implements ControllerRequestInterface
{
    /**
     * The request query
     *
     * @var HttpMessageParameters
     */
    protected $_query;

    /**
     * The request data
     *
     * @var HttpMessageParameters
     */
    protected $_data;

    /**
     * The request format
     *
     * @var string
     */
    protected $_format;

    /**
     * User object
     *
     * @var	string|object
     */
    protected $_user;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return HttpResponse
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set query parameters
        $this->setQuery($config->query);

        //Set data parameters
        $this->setData($config->data);

        //Set the format
        $this->setFormat($config->format);

        //Set the user
        $this->setUser($config->user);
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'query'     => array(),
            'data'     => array(),
            'format'   => 'html',
            'user'     => null,
            'language' => locale_get_default(),
            'timezone' => date_default_timezone_get(),
        ));

        parent::_initialize($config);
    }

    /**
     * Return the Url of the request regardless of the server
     *
     * @return HttpUrl A HttpUrl object
     */
    public function getUrl()
    {
        $url = parent::getUrl();

        //Add the query to the URL
        $url->setQuery($this->getQuery()->toArray());

        return $url;
    }

    /**
     * Set the request query
     *
     * @param  array $parameters
     * @return ControllerRequest
     */
    public function setQuery($parameters)
    {
        $this->_query = $this->getObject('lib:http.message.parameters', array('parameters' => $parameters));
        return $this;
    }

    /**
     * Get the request query
     *
     * @return HttpMessageParameters
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * Set the request data
     *
     * @param  array $parameters
     * @return ControllerRequest
     */
    public function setData($parameters)
    {
        $this->_data = $this->getObject('lib:http.message.parameters', array('parameters' => $parameters));
        return $this;
    }

    /**
     * Get the request query
     *
     * @return HttpMessageParameters
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Return the request format
     *
     * @return  string  The request format
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * Set the request format
     *
     * @param $format
     * @return ControllerRequest
     */
    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    /**
     * Set the user object
     *
     * @param UserInterface $user A request object
     * @return ControllerRequest
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
     * Returns the request language tag
     *
     * Should return a properly formatted IETF language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string
     */
    public function getLanguage()
    {
        if(!$language = $this->getUser()->getLanguage()) {
            $language = $this->getConfig()->language;
        }

        return $language;
    }

    /**
     * Returns the request timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        if(!$timezone = $this->getUser()->getLanguage()) {
            $timezone = $this->getConfig()->timezone;
        }

        return $timezone;
    }

    /**
     * Implement a virtual 'headers', 'query' and 'data class property to return their respective objects.
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

        if($name == 'query') {
            $result = $this->getQuery();
        }

        if($name == 'data') {
            $result =  $this->getData();
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

        $this->_data  = clone $this->_data;
        $this->_query = clone $this->_query;
        $this->_user  = clone $this->_user;
    }
}