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
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Abstract
 */
class TemplateEngineTwig extends TemplateEngineAbstract
{
    /**
     * The engine file types
     *
     * @var string
     */
    protected static $_file_types = array('twig');

    /**
     * The twig environment
     *
     * @var callable
     */
    protected $_twig;

    /**
     * Constructor
     *
     * @param ObjectConfig $config   An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_twig = new \Twig_Environment(null,  array(
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
            'debug'            => false,
            'autoescape'       => true,
            'strict_variables' => false,
            'optimizations'    => -1,
        ));

        parent::_initialize($config);
    }

    /**
     * Load a template by path
     *
     * @param   string  $url      The template url
     * @throws \InvalidArgumentException If the template could not be located
     * @throws \RuntimeException         If the template could not be loaded
     * @return TemplateEngineTwig
     */
    public function load($url)
    {
        parent::load($url);

        $file = $this->_content;

        $this->_twig->setLoader(new \Twig_Loader_Filesystem(dirname($file)));

        return $this;
    }

    /**
     * Render a template
     *
     * @param   array   $data   The data to pass to the template
     * @throws \RuntimeException If the template could not be evaluated
     * @return TemplateEngineTwig
     */
    public function render(array $data = array())
    {
        parent::render($data);

        $loader = $this->_twig->getLoader();
        if($loader instanceof \Twig_Loader_Filesystem) {
            $content = $this->_twig->render(basename($this->_content), $data);
        } else {
            $content = $this->_twig->render($this->_content, $data);
        }

        return $content;
    }

    /**
     * Get the template content
     *
     * @return  string
     */
    public function getContent()
    {
        $loader = $this->_twig->getLoader();

        if($loader instanceof \Twig_Loader_Filesystem) {
            $content = file_get_contents($this->_content);
        } else {
            $content = $this->_content;
        }

        return $content;
    }

    /**
     * Set the template content from a string
     *
     * @param  string  $content  The template content
     * @return TemplateEngineNooku
     */
    public function setContent($content)
    {
        parent::setContent($content);

        $this->_twig->setLoader(new \Twig_Loader_String());
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
        parent::unregisterFunction($name);

        $functions = $this->_twig->getFunctions();

        if(isset($functions[$name])) {
            unset($functions[$name]);
        }

        return $this;
    }
}
