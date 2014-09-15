<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Twig Template Engine
 *
 * @link https://github.com/fabpot/Twig
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Abstract
 */
class TemplateEngineTwig extends TemplateEngineAbstract implements \Twig_LoaderInterface
{
    /**
     * The engine file types
     *
     * @var string
     */
    protected static $_file_types = array('twig');

    /**
     * Template stack
     *
     * Used to track recursive load calls during template evaluation
     *
     * @var array
     */
    protected $_stack;

    /**
     * The twig environment
     *
     * @var callable
     */
    protected $_twig;

    /**
     * The twig template
     *
     * @var callable
     */
    protected $_twig_template;

    /**
     * Constructor
     *
     * @param ObjectConfig $config   An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Reset the stack
        $this->_stack = array();

        $this->_twig = new \Twig_Environment($this,  array(
            'cache'       => $this->_cache ? $this->_cache_path : false,
            'auto_reload' => $this->_cache_reload,
            'debug'       => $config->debug,
            'autoescape'  => $config->autoescape,
            'strict_variables' => $config->strict_variables,
            'optimizations'    => $config->optimizations,
        ));

        //Register functions in twig
        foreach($this->_functions as $name => $callable)
        {
            $function = new \Twig_SimpleFunction($name, $callable);
            $this->_twig->addFunction($function);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'autoescape'       => true,
            'strict_variables' => false,
            'optimizations'    => -1,
            'functions'        => array(
                'import' => function($url, $data) {
                    return $this->_import($url, $data);
                }
            ),
        ));

        parent::_initialize($config);
    }

    /**
     * Load a template by path
     *
     * @param   string  $url      The template url
     * @throws  InvalidArgumentException If the template could not be located
     * @throws  RuntimeException         If the template could not be loaded
     * @return TemplateEngineTwig|string Returns a string when called by Twig.
     */
    public function loadFile($url)
    {
        //Push the template on the stack
        array_push($this->_stack, array('url' => $url));

        $this->_twig_template = $this->_twig->loadTemplate($url);

        return $this;
    }

    /**
     * Set the template content from a string
     *
     * @param  string  $content  The template content
     * @return TemplateEngineNooku
     */
    public function loadString($content)
    {
        parent::loadString($content);

        //Let twig load the content by proxiing through the getSource() method.
        $this->_twig_template = $this->_twig->loadTemplate($content);

        //Push the template on the stack
        array_push($this->_stack, array('url' => ''));
        return $this;
    }

    /**
     * Render a template
     *
     * @param  array   $data   The data to pass to the template
     * @throws RuntimeException If the template could not be evaluated
     * @return string The rendered template source
     */
    public function render(array $data = array())
    {
        parent::render();

        if(!$this->_twig_template instanceof \Twig_Template) {
            throw new RuntimeException(sprintf('The template cannot be rendered'));
        }

        //Render the template
        $content = $this->_twig_template->render($data);

        //Remove the template from the stack
        array_pop($this->_stack);

        return $content;
    }

    /**
     * Unregister a function
     *
     * @param string    $name   The function name
     * @return TemplateAbstract
     */
    public function unregisterFunction($name)
    {
        parent::unregisterFunction($name);

        $functions = $this->_twig->getFunctions();

        if(isset($functions[$name])) {
            unset($functions[$name]);
        }

        return $this;
    }

    /**
     * Load the template source
     *
     * @param  string  $url The template url
     * @throws RuntimeException         If the template could not be loaded
     * @return string   The template source
     */
    protected function _load($url)
    {
        $file = $this->_locate($url);
        $type = pathinfo($file, PATHINFO_EXTENSION);

        if(in_array($type, $this->getFileTypes()))
        {
            if(!$this->_source = file_get_contents($file)) {
                throw new RuntimeException(sprintf('The template "%s" cannot be loaded.', $file));
            }
        }
        else $this->_source = $this->getTemplate()->loadFile($file)->render($this->getData());

        return $this->_source;
    }

    /**
     * Locate the template
     *
     * @param   string  $url The template url
     * @throws InvalidArgumentException If the template could not be located
     * @return string   The template real path
     */
    protected function _locate($url)
    {
        //Create the locator
        if($template = end($this->_stack)) {
            $base = $template['url'];
        } else {
            $base = null;
        }

        if(!$location = parse_url($url, PHP_URL_SCHEME)) {
            $location = $base;
        } else {
            $location = $url;
        }

        $locator = $this->getObject('template.locator.factory')->createLocator($location);

        //Locate the template
        if (!$file = $locator->setBasePath($base)->locate($url)) {
            throw new InvalidArgumentException(sprintf('The template "%s" cannot be located.', $url));
        }

        return $file;
    }

    /**
     * Import a partial template
     *
     * If importing a partial merges the data passed in with the data from the call to render. If importing a different
     * template type jump out of engine scope back to the template.
     *
     * @param   string  $url      The template url
     * @param   array   $data     The data to pass to the template
     * @return  string The rendered template content
     */
    protected function _import($url, array $data = array())
    {
        //Locate the template
        $file = $this->_locate($url);
        $type = pathinfo($file, PATHINFO_EXTENSION);

        if(in_array($type, $this->getFileTypes()))
        {
            $data   = array_merge((array) $this->getData(), $data);
            $result = $this->_twig->render($file, $data);
        }
        else  $result = $this->getTemplate()->loadFile($file)->render($data);

        return $result;
    }

    /**
     * Gets the source code of a template, given its name.
     *
     * Required by Twig_LoaderInterface Interface. Do not call directly.
     *
     * @param  string $name string The name of the template to load
     * @return string The template source code
     */
    public function getSource($name)
    {
        return $this->_load($name);
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * Required by Twig_LoaderInterface Interface. Do not call directly.
     *
     * @param  string $name string The name of the template to load
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        return crc32($name);
    }

    /**
     * Returns true if the template is still fresh.
     *
     * Required by Twig_Loader Interface. Do not call directly.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        if(is_file($name)) {
            return filemtime($name) <= $time;
        }

        return true;
    }
}
