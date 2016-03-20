<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Markdown Template Engine
 *
 * @link https://github.com/erusev/parsedown
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Template\Abstract
 */
class TemplateEngineMarkdown extends TemplateEngineAbstract
{
    /**
     * Markdown compiler
     *
     * @var callable
     */
    protected $_compiler;

    /**
     * The engine file types
     *
     * @var string
     */
    protected static $_file_types = array('md', 'markdown');

    /**
     * Constructor
     *
     * @param ObjectConfig $config   An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the markdown compiler
        if($config->compiler) {
            $this->setCompiler($config->compiler);
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
            'compiler' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Load a template by url
     *
     * @param   string  $url The template url
     * @throws \InvalidArgumentException If the template could not be located
     * @throws \RuntimeException         If the template could not be loaded
     * @throws \RuntimeException         If the template could not be compiled
     * @return TemplateEngineMarkdown
     */
    public function loadFile($url)
    {
        if(!$this->_source)
        {
            $locator = $this->getObject('template.locator.factory')->createLocator($url);

            //Locate the template
            if (!$file = $locator->locate($url)) {
                throw new \InvalidArgumentException(sprintf('The template "%s" cannot be located.', $url));
            }

            //Push the template on the stack
            array_push($this->_stack, array('url' => $url, 'file' => $file));

            if(!$cache_file = $this->isCached($file))
            {
                //Load the template
                if(!$source = file_get_contents($file)) {
                    throw new \RuntimeException(sprintf('The template "%s" cannot be loaded.', $file));
                }

                //Compile the template
                if(!$source = $this->_compile($source)) {
                    throw new \RuntimeException(sprintf('The template "%s" cannot be compiled.', $file));
                }

                $this->cache($file, $source);
                $this->_source = $source;
            }
            else  $this->_source = include $cache_file;
        }

        return $this;
    }

    /**
     * Load the template from a string
     *
     * @param  string  $souce  The template source
     * @throws \RuntimeException If the template could not be compiled
     * @return TemplateEngineMarkdown
     */
    public function loadString($source)
    {
        $file = crc32($source);

        //Push the template on the stack
        array_push($this->_stack, array('url' => '', 'file' => $file));

        if(!$this->_source = $this->isCached($file))
        {
            //Compile the template
            if(!$source = $this->_compile($source)) {
                throw new \RuntimeException(sprintf('The template content cannot be compiled.'));
            }

            $this->cache($file, $source);
            $this->_source = $source;
        }

        return $this;
    }


    /**
     * Render a template
     *
     * @param   array   $data   The data to pass to the template
     * @throws \RuntimeException If the template could not be rendered
     * @return string The rendered template source
     */
    public function render(array $data = array())
    {
        $content = parent::render($data);

        //Render the debug information
        $content = $this->_debug($content);

        //Remove the template from the stack
        array_pop($this->_stack);

        return $content;
    }

    /**
     * Get callback for compiling markdown
     *
     * @return callable
     */
    public function getCompiler()
    {
        return $this->_compiler;
    }

    /**
     * Set callback for compiling markdown
     *
     * @param  callable $compiler the compiler to set
     * @return TemplateEngineMarkdown
     */
    public function setCompiler(callable $compiler)
    {
        $this->_compiler = $compiler;
        return $this;
    }

    /**
     * Compile the template
     *
     * @param   string  $source The template source to compile
     * @return string|false The compiled template content or FALSE on failure.
     */
    protected function _compile($source)
    {
        $result = false;
        if(is_callable($this->_compiler)) {
            $result = call_user_func($this->_compiler, $source);
        }

        return $result;
    }
}
