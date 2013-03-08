<?php
/**
 * @package		Koowa_Controller
 * @subpackage  Request
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Controller Request Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Request
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
     * @param Config|null $config  An optional Config object with configuration options
     * @return HttpResponse
     */
    public function __construct(Config $config)
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
     * @param   object  An optional Config object with configuration options.
     * @return void
     */
    protected function _initialize(Config $config)
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
        $this->_query = $this->getService('lib://nooku/http.message.parameters', array('parameters' => $parameters));
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
        $this->_data = $this->getService('lib://nooku/http.message.parameters', array('parameters' => $parameters));
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
}