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
 * Template Cache
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Template\Abstract
 */
class TemplateCache extends ObjectDecorator implements TemplateInterface
{
    /**
     * The registry cache namespace
     *
     * @var boolean
     */
    protected $_namespace = 'kodekit';

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  A ObjectConfig object with optional configuration options
     * @throws \RuntimeException    If the APC PHP extension is not enabled or available
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (!self::isSupported()) {
            throw new \RuntimeException('Unable to use TemplateEngineCache. APC is not enabled.');
        }
    }

    /**
     * Get the template cache namespace
     *
     * @param string $namespace
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }

    /**
     * Get the template cache namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Load a template by url
     *
     * @param   string  $url    The template url
     * @throws \InvalidArgumentException If the template could not be found
     * @return TemplateInterface
     */
    public function loadFile($url)
    {
        $this->getDelegate()->loadFile($url);
        return $this;
    }

    /**
     * Set the template content from a string
     *
     * @param  string   $content The template content
     * @return TemplateInterface
     */
    public function loadString($content)
    {
        $this->getDelegate()->loadString($content);
        return $this;
    }

    /**
     * Render the template
     *
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @throws \InvalidArgumentException If the template could not be located
     * @return TemplateInterface
     */
    public function render(array $data = array())
    {
        $this->getDelegate()->render($data);
        return $this;
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
        return $this->getDelegate()->get($property, $default);
    }

    /**
     * Get the template data
     *
     * @return  array   The template data
     */
    public function getData()
    {
        return $this->getDelegate()->getData();
    }

    /**
     * Register a function
     *
     * @param string  $name      The function name
     * @param string  $callable  The callable
     * @return TemplateInterface
     */
    public function registerFunction($name, callable $function)
    {
        $this->getDelegate()->registerFunction($name, $function);
        return $this;
    }

    /**
     * Unregister a function
     *
     * @param string    $name   The function name
     * @return TemplateEngineInterface
     */
    public function unregisterFunction($name)
    {
        $this->getDelegate()->unregisterFunction($name);
        return $this;
    }

    /**
     * Checks if the APC PHP extension is enabled
     *
     * @return bool
     */
    public static function isSupported()
    {
        return extension_loaded('apc');
    }

    /**
     * Set the decorated translator
     *
     * @param   TemplateEngineInterface $delegate The decorated template engine
     * @return  TemplateCache
     * @throws \InvalidArgumentException If the delegate does not implement the TranslatorInterface
     */
    public function setDelegate($delegate)
    {
        if (!$delegate instanceof TemplateEngineInterface) {
            throw new \InvalidArgumentException('Delegate: '.get_class($delegate).' does not implement TemplateEngineInterface');
        }

        return parent::setDelegate($delegate);
    }

    /**
     * Get the decorated object
     *
     * @return TemplateCache
     */
    public function getDelegate()
    {
        return parent::getDelegate();
    }
}
