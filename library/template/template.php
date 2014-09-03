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
 * Template
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Abstract
 */
class Template extends TemplateAbstract implements TemplateFilterable, TemplateHelperable, ObjectInstantiable
{
    /**
     * The template parameters
     *
     * @var array
     */
    private $__parameters;

    /**
     * List of template filters
     *
     * @var array
     */
    private $__filters;


    /**
     * Filter queue
     *
     * @var	ObjectQueue
     */
    private $__filter_queue;

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

        //Set the filters
        $this->__filter_queue = $this->getObject('lib:object.queue');

        $filters = ObjectConfig::unbox($config->filters);

        foreach ($filters as $key => $value)
        {
            if (is_numeric($key)) {
                $this->addFilter($value);
            } else {
                $this->addFilter($key, $value);
            }
        }

        //Set the parameters
        $this->setParameters($config->parameters);
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
            'parameters' => array(),
            'filters'    => array(),
            'functions'  => array(
                'escape'     => array($this, 'escape'),
                'helper'     => array($this, 'invoke'),
                'parameters' => array($this, 'getParameters')
            ),
            'cache'           => false,
            'cache_namespace' => 'nooku',
        ));

        parent::_initialize($config);
    }

    /**
     * Instantiate the translator and decorate with the cache decorator if cache is enabled.
     *
     * @param   ObjectConfig            $config   A ObjectConfig object with configuration options
     * @param   ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  TranslatorInterface
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        $instance = new static($config);
        $config   = $instance->getConfig();

        if($config->cache)
        {
            $class = $manager->getClass('lib:template.cache');

            if($class::isSupported())
            {
                $instance = $instance->decorate('lib:template.cache');
                $instance->setNamespace($config->cache_namespace);
            }
        }

        return $instance;
    }

    /**
     * Load a template by path
     *
     * @param   string  $url      The template url
     * @throws \InvalidArgumentException If the template could not be located
     * @return Template
     */
    public function loadFile($url)
    {
        //Locate the template
        $locator = $this->getObject('template.locator.factory')->createLocator($url);

        if (!$file = $locator->locate($url)) {
            throw new \InvalidArgumentException(sprintf('The template "%s" cannot be located.', $url));
        }

        //Create the template engine
        $config = array(
            'template'  => $this,
            'functions' => $this->_functions
        );

        $this->_source = $this->getObject('template.engine.factory')
            ->createEngine($file, $config)
            ->loadFile($url);

        return $this;
    }

    /**
     * Set the template content from a string
     *
     * Overrides TemplateInterface::setContent() and allows to define the type of content. If a type is set
     * an engine for the type will be created. If no type is set we will assumed the content has already been
     * rendered.
     *
     * @param  string   $source  The template source
     * @param  integer  $type    The template type.
     * @return TemplateAbstract
     */
    public function loadString($source, $type = null)
    {
        if($type)
        {
            //Create the template engine
            $config = array(
                'template'  => $this,
                'functions' => $this->_functions
            );

            $this->_source = $this->getObject('template.engine.factory')
                ->createEngine($type, $config)
                ->loadString($source);
        }
        else parent::loadString($source);

        return $this;
    }

    /**
     * Render the template
     *
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @return string The rendered template source
     */
    public function render(array $data = array())
    {
        parent::render($data);

        if($this->_source instanceof TemplateEngineInterface)
        {
            $this->_source = $this->_source->render($data);
            $this->_source = $this->filter();
        }

        return $this->_source;
    }

    /**
     * Filter template content
     *
     * @return string The filtered template source
     */
    public function filter()
    {
        if(is_string($this->_source))
        {
            //Filter the template
            foreach($this->__filter_queue as $filter) {
                $filter->filter($this->_source);
            }
        }

        return $this->_source;
    }

    /**
     * Escape a string
     *
     * By default the function uses htmlspecialchars to escape the string
     *
     * @param string $string String to to be escape
     * @return string Escaped string
     */
    public function escape($string)
    {
        if(is_string($string)) {
            $string = htmlspecialchars($string, ENT_COMPAT | ENT_SUBSTITUTE, 'UTF-8', false);
        }

        return $string;
    }

    /**
     * Set the template parameters
     *
     * @param  array $parameters Set the template parameters
     * @return Template
     */
    public function setParameters($parameters)
    {
        $this->__parameters = new ObjectConfig($parameters);
        return $this;
    }

    /**
     * Get the model state object
     *
     * @return ObjectConfigInterface
     */
    public function getParameters()
    {
        return $this->__parameters;
    }

    /**
     * Invoke a template helper
     *
     * This function accepts a partial identifier, in the form of helper.method or schema:package.helper.method. If
     * a partial identifier is passed a full identifier will be created using the template identifier.
     *
     * If the state have the same string keys, then the parameter value for that key will overwrite the state.
     *
     * @param    string   $identifier Name of the helper, dot separated including the helper function to call
     * @param    array    $params     An optional associative array of functions parameters to be passed to the helper
     * @return   string   Helper output
     * @throws   \BadMethodCallException If the helper function cannot be called.
     */
    public function invoke($identifier, $params = array())
    {
        //Get the function and helper based on the identifier
        $parts      = explode('.', $identifier);
        $function   = array_pop($parts);
        $identifier = array_pop($parts);

        //Handle schema:package.helper.function identifiers
        if(!empty($parts)) {
            $identifier = implode('.', $parts).'.template.helper.'.$identifier;
        }

        $helper = $this->createHelper($identifier, $params);

        //Call the helper function
        if (!is_callable(array($helper, $function))) {
            throw new \BadMethodCallException(get_class($helper) . '::' . $function . ' not supported.');
        }

        //Merge the parameters if helper asks for it
        if ($helper instanceof TemplateHelperParameterizable) {
            $params = array_merge($this->getParameters()->toArray(), $params);
        }

        return $helper->$function($params);
    }

    /**
     * Get a template helper
     *
     * @param    mixed $helper ObjectIdentifierInterface
     * @param    array $config An optional associative array of configuration settings
     *
     * @throws \UnexpectedValueException
     * @return  TemplateHelperInterface
     */
    public function createHelper($helper, $config = array())
    {
        //Create the complete identifier if a partial identifier was passed
        if (is_string($helper) && strpos($helper, '.') === false)
        {
            $identifier = $this->getIdentifier()->toArray();

            if($identifier['type'] != 'lib') {
                $identifier['path'] = array('template', 'helper');
            } else {
                $identifier['path'] = array('helper');
            }

            $identifier['name'] = $helper;
        }
        else $identifier = $this->getIdentifier($helper);

        //Create the template helper
        $helper = $this->getObject($identifier, array_merge($config, array('template' => $this)));

        //Check the helper interface
        if (!($helper instanceof TemplateHelperInterface))
        {
            throw new \UnexpectedValueException(
                "Template helper $identifier does not implement TemplateHelperInterface"
            );
        }

        return $helper;
    }

    /**
     * Attach a filter for template transformation
     *
     * @param   mixed  $filter An object that implements ObjectInterface, ObjectIdentifier object
     *                         or valid identifier string
     * @param   array $config  An optional associative array of configuration settings
     * @return TemplateAbstract
     */
    public function addFilter($filter, $config = array())
    {
        //Create the complete identifier if a partial identifier was passed
        if (is_string($filter) && strpos($filter, '.') === false)
        {
            $identifier = $this->getIdentifier()->toArray();
            $identifier['path'] = array('template', 'filter');
            $identifier['name'] = $filter;

            $identifier = $this->getIdentifier($identifier);
        }
        else $identifier = $this->getIdentifier($filter);

        if (!$this->hasFilter($identifier->name))
        {
            $filter = $this->getObject($identifier, array_merge($config, array('template' => $this)));

            if (!($filter instanceof TemplateFilterInterface))
            {
                throw new \UnexpectedValueException(
                    "Template filter $identifier does not implement TemplateFilterInterface"
                );
            }

            //Store the filter
            $this->__filters[$filter->getIdentifier()->name] = $filter;

            //Enqueue the filter
            $this->__filter_queue->enqueue($filter, $filter->getPriority());
        }

        return $this;
    }

    /**
     * Check if a filter exists
     *
     * @param 	string	$filter The name of the filter
     * @return  boolean	TRUE if the filter exists, FALSE otherwise
     */
    public function hasFilter($filter)
    {
        return isset($this->__filters[$filter]);
    }

    /**
     * Get a filter by identifier
     *
     * @param   mixed $filter       An object that implements ObjectInterface, ObjectIdentifier object
     *                              or valid identifier string
     * @throws \UnexpectedValueException
     * @return TemplateFilterInterface|null
     */
    public function getFilter($filter)
    {
        $result = null;

        if(isset($this->__filters[$filter])) {
            $result = $this->__filters[$filter];
        }

        return $result;
    }
}
