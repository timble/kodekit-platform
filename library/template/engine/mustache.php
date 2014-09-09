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
 * Mustache Template Engine
 *
 * @link https://github.com/bobthecow/mustache.php
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Abstract
 */
class TemplateEngineMustache extends TemplateEngineAbstract implements \Mustache_Loader
{
    /**
     * The engine file types
     *
     * @var string
     */
    protected static $_file_types = array('mustache');

    /**
     * Template stack
     *
     * Used to track recursive load calls during template evaluation
     *
     * @var array
     */
    protected $_stack;

    /**
     * The mustache engine
     *
     * @var callable
     */
    protected $_mustache;

    /**
     * The twig template
     *
     * @var callable
     */
    protected $_mustache_template;

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

        $this->_mustache = new \Mustache_Engine(array(
            'loader' => $this,
            'cache'  => $this->_cache ? $this->_cache_path : null,
            'escape' => function($value) {
                    return $this->getTemplate()->escape($value);
             },
            'strict_callables' => $this->getConfig()->strict_callables,
            'pragmas'          => $this->getConfig()->pragmas,
            'helpers'          => $this->_functions
        ));
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
            'strict_callables' => false,
            'pragmas'          => array(\Mustache_Engine::PRAGMA_FILTERS),
        ));

        parent::_initialize($config);
    }

    /**
     * Load a template by path
     *
     * @param   string  $url The template url
     * @throws \InvalidArgumentException If the template could not be located
     * @throws \RuntimeException         If the template could not be loaded
     * @return TemplateEngineMustache|string Returns a string when called by Mustache
     */
    public function loadFile($url)
    {
        //Push the template on the stack
        array_push($this->_stack, array('url' => $url));

        $this->_mustache_template = $this->_mustache->loadTemplate($url);

        //Load partial templates
        return $this;
    }

    /**
     * Set the template content from a string
     *
     * @param  string  $source  The template source
     * @return TemplateEngineMustache
     */
    public function loadString($source)
    {
        parent::loadString($source);

        //Let mustache load the source by proxiing through the load() method.
        $this->_mustache_template = $this->_mustache->loadTemplate($source);

        //Push the template on the stack
        array_push($this->_stack, array('url' => ''));

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
        parent::render($data);

        if(!$this->_mustache_template instanceof \Mustache_Template) {
            throw new \RuntimeException(sprintf('The template cannot be rendered'));
        }

        //Render the template
        $content = $this->_mustache_template->render($data);

        //Remove the template from the stack
        array_pop($this->_stack);

        return $content;
    }

    /**
     * Load the template source
     *
     * @param   string  $url The template url
     * @throws \RuntimeException         If the template could not be loaded
     * @return string   The template source
     */
    protected function _load($url)
    {
        $file = $this->_locate($url);
        $type = pathinfo($file, PATHINFO_EXTENSION);

        if(in_array($type, $this->getFileTypes()))
        {
            //Push the template on the stack
            array_push($this->_stack, array('url' => $url, 'file' => $file));

            if(!$this->_source = file_get_contents($file)) {
                throw new \RuntimeException(sprintf('The template "%s" cannot be loaded.', $file));
            }
        }
        else $this->_source = $this->getTemplate()->loadFile($file)->render($this->getData());

        return $this->_source;
    }

    /**
     * Locate the template
     *
     * @param   string  $url The template url
     * @throws \InvalidArgumentException If the template could not be located
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
            throw new \InvalidArgumentException(sprintf('The template "%s" cannot be located.', $url));
        }

        return $file;
    }

    /**
     * Gets the source code of a template, given its name.
     *
     * Required by Mustache_Loader Interface. Do not call directly.
     *
     * @param  string $name string The name of the template to load
     * @return string The template source code
     */
    public function load($name)
    {
        return $this->_load($name);
    }
}
