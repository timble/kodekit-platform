<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Template
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
abstract class TemplateAbstract extends Object implements TemplateInterface
{
    /**
     * Tracks the status the template
     *
     * Available template status values are defined as STATUS_ constants
     *
     * @var string
     */
    protected $_status = null;

    /**
     * The template path
     *
     * @var string
     */
    protected $_path;

    /**
     * The template data
     *
     * @var array
     */
    protected $_data;

    /**
     * The template contents
     *
     * @var string
     */
    protected $_content;

    /**
     * The template locators
     *
     * @var array
     */
    protected $_locators;

    /**
     * View object or identifier
     *
     * @var    string|object
     */
    protected $_view;

    /**
     * Template stack
     *
     * Used to track recursive load calls during template evaluation
     *
     * @var array
     * @see load()
     */
    protected $_stack;

    /**
     * List of template filters
     *
     * @var array
     */
    protected $_filters;

    /**
     * Filter queue
     *
     * @var	ObjectQueue
     */
    protected $_queue;

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

        // Set the view identifier
        $this->_view = $config->view;

        // Set the template data
        $this->_data = $config->data;

        //Set the filter queue
        $this->_queue = $this->getObject('lib:object.queue');

        //Register the loaders
        $this->_locators = ObjectConfig::unbox($config->locators);

        //Attach the filters
        $filters = ObjectConfig::unbox($config->filters);

        foreach ($filters as $key => $value)
        {
            if (is_numeric($key)) {
                $this->attachFilter($value);
            } else {
                $this->attachFilter($key, $value);
            }
        }

        //Reset the stack
        $this->_stack = array();
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
            'data'     => array(),
            'view'     => null,
            'filters'  => array(),
            'locators' => array('com' => 'lib:template.locator.component')
        ));

        parent::_initialize($config);
    }

    /**
     * Load a template by path
     *
     * @param   string  $path     The template path
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @throws \InvalidArgumentException If the template could not be found
     * @return TemplateAbstract
     */
    public function load($path, $data = array(), $status = self::STATUS_LOADED)
    {
        $parts = parse_url( $path );

        //Set the default type is not scheme can be found
        if(!isset($parts['scheme'])) {
            $type = 'com';
        } else {
            $type = $parts['scheme'];
        }

        //Check of the file exists
        if (!$template = $this->getLocator($type)->locate($path)) {
            throw new \InvalidArgumentException('Template "' . $path . '" not found');
        }

        //Push the path on the stack
        array_push($this->_stack, $path);

        //Set the status
        $this->_status = $status;

        //Load the file
        $this->_content = $this->getObject('lib:filesystem.stream')->open($template)->getContent();

        //Compile and evaluate partial templates
        if(count($this->_stack) > 1)
        {
            if(!($status & self::STATUS_COMPILED)) {
                $this->compile();
            }

            if(!($status & self::STATUS_EVALUATED)) {
                $this->evaluate($data);
            }
        }

        return $this;
    }

    /**
     * Parse and compile the template to PHP code
     *
     * This function passes the template through compile filter queue and returns the result.
     *
     * @return TemplateAbstract
     */
    public function compile()
    {
        if(!($this->_status & self::STATUS_COMPILED))
        {
            foreach($this->_queue as $filter)
            {
                if($filter instanceof TemplateFilterCompiler) {
                    $filter->compile($this->_content);
                }
            }

            //Set the status
            $this->_status ^= self::STATUS_COMPILED;
        }

        return $this;
    }

    /**
     * Evaluate the template using a simple sandbox
     *
     * This function writes the template to a temporary file and then includes it.
     *
     * @param  array   $data  An associative array of data to be extracted in local template scope
     * @return TemplateAbstract
     * @see tempnam()
     */
    public function evaluate($data = array())
    {
        if(!($this->_status & self::STATUS_EVALUATED))
        {
            //Merge the data
            $this->_data = array_merge((array) $this->_data, $data);

            //Create temporary file
            $tempfile = tempnam(sys_get_temp_dir(), 'tmpl');
            $this->getObject('manager')->getClassLoader()->setAlias($this->getPath(), $tempfile);

            //Write the template to the file
            $handle = fopen($tempfile, "w+");
            fwrite($handle, $this->_content);
            fclose($handle);

            //Include the file
            extract($this->_data, EXTR_SKIP);

            ob_start();
            include $tempfile;
            $this->_content = ob_get_clean();

            unlink($tempfile);

            //Remove the path from the stack
            array_pop($this->_stack);

            //Set the status
            $this->_status ^= self::STATUS_EVALUATED;
        }

        return $this;
    }

    /**
     * Process the template
     *
     * This function passes the template through the render filter queue
     *
     * @return TemplateAbstract
     */
    public function render()
    {
        if(!($this->_status & self::STATUS_RENDERED))
        {
            foreach($this->_queue as $filter)
            {
                if($filter instanceof TemplateFilterRenderer) {
                    $filter->render($this->_content);
                }
            }

            //Set the status
            $this->_status ^= self::STATUS_RENDERED;
        }

        return $this;
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
        return htmlspecialchars($string);
    }

    /**
     * Translates a string and handles parameter replacements
     *
     * @param string $string String to translate
     * @param array  $parameters An array of parameters
     * @return string Translated string
     */
    public function translate($string, array $parameters = array())
    {
        return \JText::_($string);
    }

    /**
     * Get the template path
     *
     * @return	string
     */
    public function getPath()
    {
        return end($this->_stack);
    }

    /**
     * Get the format
     *
     * @return 	string 	The format of the view
     */
    public function getFormat()
    {
        return $this->getView()->getFormat();
    }

    /**
     * Get the template data
     *
     * @return  mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Set the template data
     *
     * @param  array   $data     The template data
     * @return TemplateAbstract
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * Get the template content
     *
     * @return  string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Set the template content from a string
     *
     * @param  string   $string     The template content
     * @param  integer  $status     The template state
     * @return TemplateAbstract
     */
    public function setContent($content, $status = self::STATUS_LOADED)
    {
        $this->_content = $content;
        $this->_status  = $status;

        return $this;
    }

    /**
     * Get the view object attached to the template
     *
     * @throws	\UnexpectedValueException	If the views doesn't implement the ViewInterface
     * @return  ViewInterface
     */
    public function getView()
    {
        if(!$this->_view instanceof ViewInterface)
        {
            //Make sure we have a view identifier
            if(!($this->_view instanceof ObjectIdentifier)) {
                $this->setView($this->_view);
            }

            $this->_view = $this->getObject($this->_view);

            //Make sure the view implements ViewInterface
            if(!$this->_view instanceof ViewInterface)
            {
                throw new \UnexpectedValueException(
                    'View: '.get_class($this->_view).' does not implement ViewInterface'
                );
            }
        }

        return $this->_view;
    }

    /**
     * Method to set a view object attached to the controller
     *
     * @param	mixed	$view An object that implements ObjectInterface, ObjectIdentifier object
     * 					      or valid identifier string
     * @return TemplateAbstract
     */
    public function setView($view)
    {
        if(!($view instanceof ViewInterface))
        {
            if(is_string($view) && strpos($view, '.') === false )
            {
                $identifier			= clone $this->getIdentifier();
                $identifier->path	= array('view', $view);
                $identifier->name = 'html';
            }
            else $identifier = $this->getIdentifier($view);

            $view = $identifier;
        }

        $this->_view = $view;

        return $this;
    }

    /**
     * Get a filter by identifier
     *
     * @param   mixed    $filter    An object that implements ObjectInterface, ObjectIdentifier object
     *                              or valid identifier string
     * @param   array    $config    An optional associative array of configuration settings
     * @return TemplateFilterInterface
     */
    public function getFilter($filter, $config = array())
    {
        //Create the complete identifier if a partial identifier was passed
        if (is_string($filter) && strpos($filter, '.') === false)
        {
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('template', 'filter');
            $identifier->name = $filter;
        }
        else $identifier = $this->getIdentifier($filter);

        if (!isset($this->_filters[$identifier->name]))
        {
            $filter = $this->getObject($identifier, array_merge($config, array('template' => $this)));

            if (!($filter instanceof TemplateFilterInterface))
            {
                throw new \UnexpectedValueException(
                    "Template filter $identifier does not implement TemplateFilterInterface"
                );
            }

            $this->_filters[$filter->getIdentifier()->name] = $filter;
        }
        else $filter = $this->_filters[$identifier->name];

        return $filter;
    }

    /**
     * Attach a filter for template transformation
     *
     * @param   mixed  $filter An object that implements ObjectInterface, ObjectIdentifier object
     *                         or valid identifier string
     * @param   array $config  An optional associative array of configuration settings
     * @return TemplateAbstract
     */
    public function attachFilter($filter, $config = array())
    {
        if (!($filter instanceof TemplateFilterInterface)) {
            $filter = $this->getFilter($filter, $config);
        }

        //Enqueue the filter
        $this->_queue->enqueue($filter, $filter->getPriority());

        return $this;
    }

    /**
     * Get a template helper
     *
     * @param    mixed    $helper ObjectIdentifierInterface
     * @param    array    $config An optional associative array of configuration settings
     * @return  TemplateHelperInterface
     */
    public function getHelper($helper, $config = array())
    {
        //Create the complete identifier if a partial identifier was passed
        if (is_string($helper) && strpos($helper, '.') === false)
        {
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('template', 'helper');
            $identifier->name = $helper;
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
     * Load a template helper
     *
     * This function accepts a partial identifier, in the form of helper.function or schema:package.helper.function. If
     * a partial identifier is passed a full identifier will be created using the template identifier.
     *
     * If the view state have the same string keys, then the parameter value for that key will overwrite the state.
     *
     * @param    string   $identifier Name of the helper, dot separated including the helper function to call
     * @param    array    $params     An optional associative array of functions parameters to be passed to the helper
     * @return   string   Helper output
     * @throws   \BadMethodCallException If the helper function cannot be called.
     */
    public function renderHelper($identifier, $params = array())
    {
        //Get the function and helper based on the identifier
        $parts      = explode('.', $identifier);
        $function   = array_pop($parts);
        $identifier = array_pop($parts);

        //Handle schema:package.helper.function identifiers
        if(!empty($parts)) {
            $identifier = implode('.', $parts).'.template.helper.'.$identifier;
        }

        $helper = $this->getHelper($identifier, $params);

        //Call the helper function
        if (!is_callable(array($helper, $function))) {
            throw new \BadMethodCallException(get_class($helper) . '::' . $function . ' not supported.');
        }

        //Merge the view state with the helper params
        $view = $this->getView();

        if(StringInflector::isPlural($view->getName()))
        {
            if($state = $view->getModel()->getState()) {
                $params = array_merge( $state->getValues(), $params);
            }
        }
        else
        {
            if($item = $view->getModel()->getRow()) {
                $params = array_merge( $item->toArray(), $params);
            }
        }

        return $helper->$function($params);
    }

    /**
     * Register a template locator
     *
     * @param TemplateLoaderInterface $locator
     * @return TemplateAbstract
     */
    public function registerLocator(TemplateLocatorInterface $locator)
    {
        $this->_locators[$locator->getType()] = $locator;
        return $this;
    }

    /**
     * Get a registered template locator based on his type
     *
     * @return TemplateLoaderInterface|null  Returns the template loader or NULL if the loader can not be found.
     */
    public function getLocator($type, $config = array())
    {
        $locator = null;
        if(isset($this->_locators[$type]))
        {
            $locator = $this->_locators[$type];

            if(!$locator instanceof TemplateLocatorInterface)
            {
                //Create the complete identifier if a partial identifier was passed
                if (is_string($locator) && strpos($locator, '.') === false)
                {
                    $identifier = clone $this->getIdentifier();
                    $identifier->path = array('template', 'locator');
                    $identifier->name = $locator;
                }
                else $identifier = $this->getIdentifier($locator);

                $locator = $this->getObject($identifier, array_merge($config, array('template' => $this)));

                if (!($locator instanceof TemplateLocatorInterface))
                {
                    throw new \UnexpectedValueException(
                        "Template loader $identifier does not implement TemplateLocatorInterface"
                    );
                }

                $this->_loaders[$type] = $locator;
            }
        }

        return $locator;
    }

    /**
     * Check if the template is loaded
     *
     * @return boolean  Returns TRUE if the template is loaded. FALSE otherwise
     */
    public function isLoaded()
    {
        return $this->_status & self::STATUS_LOADED;
    }

    /**
     * Check if the template is compiled
     *
     * @return boolean  Returns TRUE if the template is compiled. FALSE otherwise
     */
    public function isCompiled()
    {
        return $this->_status & self::STATUS_COMPILED;
    }

    /**
     * Check if the template is evaluated
     *
     * @return boolean  Returns TRUE if the template is evaluated. FALSE otherwise
     */
    public function isEvaluated()
    {
        return $this->_status & self::STATUS_EVALUATED;
    }

    /**
     * Check if the template is rendered
     *
     * @return boolean  Returns TRUE if the template is rendered. FALSE otherwise
     */
    public function isRendered()
    {
        return $this->_status & self::STATUS_RENDERED;
    }

    /**
     * Returns the template contents
     *
     * When casting to a string the template content will be compiled, evaluated and rendered.
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->getContent();
    }
}