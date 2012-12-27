<?php
/**
 * @version		$Id: abstract.php 4948 2012-09-03 23:05:48Z johanjanssens $
 * @package		Koowa_Controller
 * @subpackage  Request
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Controller Request Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Request
 */
class KControllerRequest extends KHttpRequest implements KControllerRequestInterface
{
    /**
     * The request query
     *
     * @var KHttpMessageParameters
     */
    protected $_query;

    /**
     * The request data
     *
     * @var KHttpMessageParameters
     */
    protected $_data;

    /**
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return KHttpResponse
     */
    public function __construct(KConfig $config)
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
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
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
     * @return KControllerRequest
     */
    public function setQuery($parameters)
    {
        $this->_query = $this->getService('koowa:http.message.parameters', array('parameters' => $parameters));
        return $this;
    }

    /**
     * Get the request query
     *
     * @return KHttpMessageParameters
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * Set the request data
     *
     * @param  array $parameters
     * @return KControllerRequest
     */
    public function setData($parameters)
    {
        $this->_data = $this->getService('koowa:http.message.parameters', array('parameters' => $parameters));
        return $this;
    }

    /**
     * Get the request query
     *
     * @return KHttpMessageParameters
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