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
 * Abstract Template
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Template\Abstract
 */
abstract class TemplateAbstract extends Object implements TemplateInterface
{
    /**
     * List of template functions
     *
     * @var array
     */
    protected $_functions;

    /**
     * The template data
     *
     * @var array
     */
    protected $_data;

    /**
     * The template source
     *
     * @var string
     */
    protected $_source;

    /**
     * Debug
     *
     * @var boolean
     */
    protected $_debug;

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     *
     * @param ObjectConfig $config   An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Reset the data
        $this->_data = array();

        //Reset the content
        $this->_source = null;

        //Set debug
        $this->_debug  = $config->debug;

        //Register the functions
        $functions = ObjectConfig::unbox($config->functions);

        foreach ($functions as $name => $callback) {
            $this->registerFunction($name, $callback);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'debug'      => \Kodekit::getInstance()->isDebug(),
            'functions' => array()
        ));

        parent::_initialize($config);
    }

    /**
     * Load a template by path
     *
     * @param   string  $url      The template url
     * @throws \InvalidArgumentException If the template could not be located
     * @throws \RuntimeException         If the template could not be loaded
     * @return TemplateAbstract
     */
    public function loadFile($url)
    {
        //Locate the template
        $locator = $this->getObject('template.locator.factory')->createLocator($url);

        if (!$file = $locator->locate($url)) {
            throw new \InvalidArgumentException(sprintf('The template "%s" cannot be located.', $url));
        }

        //Load the template
        if(!$source = file_get_contents($file)) {
            throw new \RuntimeException(sprintf('The template "%s" cannot be loaded.', $file));
        }

        $this->_source = $source;

        return $this;
    }

    /**
     * Set the template source from a string
     *
     * @param  string   $content The template content
     * @return TemplateAbstract
     */
    public function loadString($source)
    {
        $this->_source = $source;
        return $this;
    }

    /**
     * Render the template
     *
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @return string The rendered template
     */
    public function render(array $data = array())
    {
        $this->_data = $data;

        return $this->_source;
    }

    /**
     * Get a template property
     *
     * @param   string  $property The property name.
     * @param   mixed   $default  Default value to return.
     * @return  string  The property value.
     */
    public function get($property, $default = null)
    {
        return isset($this->_data[$property]) ? $this->_data[$property] : $default;
    }

    /**
     * Get the template data
     *
     * @return  array   The template data
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Register a function
     *
     * @param string  $name      The function name
     * @param string  $callable  The callable
     * @return TemplateAbstract
     */
    public function registerFunction($name, callable $function)
    {
        $this->_functions[$name] = $function;
        return $this;
    }

    /**
     * Unregister a function
     *
     * @param string    $name   The function name
     * @return TemplateAbstract
     */
    public function unregisterFunction($name)
    {
        if( $this->_functions[$name]) {
            unset($this->_functions[$name]);
        }

        return $this;
    }

    /**
     * Enable or disable debug
     *
     * @param bool $debug True or false.
     * @return TemplateAbstract
     */
    public function setDebug($debug)
    {
        $this->_debug = (bool) $debug;
        return $this;
    }

    /**
     * Check if the template is running in debug mode
     *
     * @return bool
     */
    public function isDebug()
    {
        return $this->_debug;
    }

    /**
     * Get a template data property
     *
     * @param   string  $property The property name.
     * @return  string  The property value.
     */
    final public function __get($property)
    {
        return $this->get($property);
    }

    /**
     * Call template functions
     *
     * This method will not throw a BadMethodCallException as it"s parent does. Instead if the method is not callable
     * it will return NULL
     *
     * @param  string $method    The function name
     * @param  array  $arguments The function arguments
     * @return mixed|null   Return NULL If method could not be found
     */
    public function __call($method, $arguments)
    {
        if(!isset($this->_functions[$method]))
        {
            if (is_callable(array($this, $method))) {
                $result = parent::__call($method, $arguments);
            } else {
                $result = null;
            }
        }
        else $result = call_user_func_array($this->_functions[$method], $arguments);

        return $result;
    }
}