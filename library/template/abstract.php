<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Template
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Abstract
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

        //Register the functions
        $functions = (array)ObjectConfig::unbox($config->functions);

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
     * @return  array   The view data
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
     * @param  string $method    The function name
     * @param  array  $arguments The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        if(isset($this->_functions[$method])) {
            $result = call_user_func_array($this->_functions[$method], $arguments);
        } else {
            $result = parent::__call($method, $arguments);
        }

        return $result;
    }
}