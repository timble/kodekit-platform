<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Controller Request
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
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
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'query' => array(),
            'data'  => array(),
        ));

        parent::_initialize($config);
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
     * @param   string  $format The default format
     * @return  string  The request format
     */
    public function getFormat($format = 'html')
    {
        if($this->_query->has('format')) {
            $format = $this->_query->get('format', 'alpha');
        }

        return $format;
    }

    /**
     * Set the request format
     *
     * @param $format
     * @return ControllerRequest
     */
    public function setFormat($format)
    {
        $this->_query->set('format', $format);
        return $this;
    }

    /**
     * Implement a virtual 'headers', 'query' and 'data class property to return their respective objects.
     *
     * @param   string $name  The property name.
     * @return  string $value The property value.
     */
    public function __get($name)
    {
        if($name == 'headers') {
            return $this->getHeaders();
        }

        if($name == 'query') {
            return $this->getQuery();
        }

        if($name == 'data') {
            return $this->getData();
        }

        return parent::__get($name);
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
    }
}