<?php
/**
 * @package        Koowa_Template
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract Template class
 *
 * @author        Johan Janssens <johan@nooku.org>
 * @package    Koowa_Template
 */
abstract class TemplateAbstract extends Object implements TemplateInterface
{
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
     * The set of template filters for templates
     *
     * @var array
     */
    protected $_filters;

    /**
     * View object or identifier
     *
     * @var    string|object
     */
    protected $_view;

    /**
     * Template stack
     *
     * Used to track recursive loadFile calls during template evaluation
     *
     * @var array
     * @see loadFile()
     */
    protected $_stack;

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the constructor private
     *
     * @param Config $config   An optional Config object with configuration options
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        // Set the view identifier
        $this->_view = $config->view;

        // Set the template data
        $this->_data = $config->data;

        // Mixin a command chain
        $this->mixin(new MixinCommand($config->append(array('mixer' => $this))));

        //Attach the filters
        $this->attachFilter($config->filters);

        //Reset the counter
        $this->_stack = array();
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Config $config  An optional Config object with configuration options.
     * @return void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'data'             => array(),
            'filters'          => array(),
            'view'             => null,
            'command_chain'    => $this->getService('lib://nooku/command.chain'),
            'dispatch_events'  => false,
            'enable_callbacks' => false,
        ));

        parent::_initialize($config);
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
     * Get the template content
     *
     * @return  string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Get the template file identifier
     *
     * @return	string
     */
    public function getPath()
    {
        return current($this->_stack);
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
            if(!($this->_view instanceof ServiceIdentifier)) {
                $this->setView($this->_view);
            }

            $this->_view = $this->getService($this->_view);

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
     * @param	mixed	$view An object that implements ServiceInterface, ServiceIdentifier object
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
     * Load a template by path
     *
     * @param   string  $path     The template path
     * @param   array   $data     An associative array of data to be extracted in local template scope
     * @throws \InvalidArgumentException If the template could not be found
     * @return TemplateAbstract
     */
    public function loadFile($file, $data = array())
    {
        if(strpos($file, 'com://') === 0)
        {
            $info  = pathinfo( $file );

            //Get the filepath based on the identifier
            $path  = $this->getIdentifier($info['dirname'].'/'.$info['filename'])->filepath;

            //Add the templates folder
            $path = dirname($path).'/templates/'.basename($path);

            //Add the format
            $path  = str_replace('.php', '.'.$info['extension'].'.php', $path);
        }
        else
        {
            $path  = dirname($this->getPath());
            $path .= '/'.$file.'.php';
        }

        //Find the template
        $template = $this->findFile($path);

        //Check of the file exists
        if (!file_exists($template)) {
            throw new \InvalidArgumentException('Template "' . $file . '" not found');
        }

        //Push the path on the stack
        array_push($this->_stack, $path);

        //Load the file
        $contents = file_get_contents($template);
        $this->loadString($contents, $data);

        //Pop the path of the stack
        array_pop($this->_stack);

        return $this;
    }

    /**
     * Load a template from a string
     *
     * @param  string   $string     The template contents
     * @param  array    $data       An associative array of data to be extracted in local template scope
     * @return TemplateAbstract
     */
    public function loadString($string, $data = array())
    {
        $this->_content = $string;

        //Merge the data
        $this->_data = array_merge((array)$this->_data, $data);

        //Render subtemplates
        if(count($this->_stack)) {
            $this->render();
        }

        return $this;
    }

    /**
     * Render the template
     *
     * @return string  The rendered data
     */
    public function render()
    {
        //Parse the template
        $this->_parse($this->_content);

        //Evaluate the template
        $this->_evaluate($this->_content);

        //Process the template only at the end of the render cycle.
        if(!count($this->_stack)) {
            $this->_process($this->_content);
        }

        return $this->_content;
    }

    /**
     * Check if the template is in a render cycle
     *
     * @return boolean Return TRUE if the template is being rendered
     */
    public function isRendering()
    {
        return (bool) count($this->_stack);
    }

    /**
     * Get a filter by identifier
     *
     * @param   mixed    $filter    An object that implements ServiceInterface, ServiceIdentifier object
                                    or valid identifier string
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
            $filter = $this->getService($identifier, array_merge($config, array('template' => $this)));

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
     * Attach one or more filters for template transformation
     *
     * @param array $filters Array of one or more behaviors to add.
     * @return TemplateAbstract
     */
    public function attachFilter($filters)
    {
        $filters = (array)Config::unbox($filters);

        foreach ($filters as $filter)
        {
            if (!($filter instanceof TemplateFilterInterface)) {
                $filter = $this->getFilter($filter);
            }

            //Enqueue the filter in the command chain
            $this->getCommandChain()->enqueue($filter);
        }

        return $this;
    }

    /**
     * Get a template helper
     *
     * @param    mixed    $helper ServiceIdentifierInterface
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
        $helper = $this->getService($identifier, array_merge($config, array('template' => $this)));

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
     * This functions accepts a partial identifier, in the form of helper.function. If a partial identifier is passed a
     * full identifier will be created using the template identifier.
     *
     * @param    string   $identifier Name of the helper, dot separated including the helper function to call
     * @param    array    $params     An optional associative array of functions parameters to be passed to the helper
     * @return   string   Helper output
     * @throws   \BadMethodCallException If the helper function cannot be called.
     */
    public function renderHelper($identifier, $params = array())
    {
        //Get the function to call based on the $identifier
        $parts    = explode('.', $identifier);
        $function = array_pop($parts);

        $helper = $this->getHelper(implode('.', $parts), $params);

        //Call the helper function
        if (!is_callable(array($helper, $function))) {
            throw new \BadMethodCallException(get_class($helper) . '::' . $function . ' not supported.');
        }

        return $helper->$function($params);
    }

    /**
     * Searches for the file
     *
     * @param   string  $file The file path to look for.
     * @return  mixed   The full path and file name for the target file, or FALSE if the file is not found
     */
    public function findFile($file)
    {
        $result = false;
        $path = dirname($file);

        // is the path based on a stream?
        if (strpos($path, '://') === false)
        {
            // not a stream, so do a realpath() to avoid directory
            // traversal attempts on the local file system.
            $path = realpath($path); // needed for substr() later
            $file = realpath($file);
        }

        // The substr() check added to make sure that the realpath()
        // results in a directory registered so that non-registered directores
        // are not accessible via directory traversal attempts.
        if (file_exists($file) && substr($file, 0, strlen($path)) == $path) {
            $result = $file;
        }

        // could not find the file in the set of paths
        return $result;
    }

    /**
     * Parse and compile the template to PHP code
     *
     * This function passes the template through read filter chain and returns the result.
     *
     * @return string The parsed data
     */
    protected function _parse(&$content)
    {
        $context = $this->getCommandContext();

        $context->data = $content;
        $this->getCommandChain()->run(TemplateFilter::MODE_READ, $context);
        $content = $context->data;
    }

    /**
     * Evaluate the template using a simple sandbox
     *
     * This function writes the template to a temporary file and then includes it.
     *
     * @return string The evaluated data
     * @see tempnam()
     */
    protected function _evaluate(&$content)
    {
        //Create temporary file
        $tempfile = tempnam(sys_get_temp_dir(), 'tmpl');
        $this->getService('loader')->setAlias($this->getPath(), $tempfile);

        //Write the template to the file
        $handle = fopen($tempfile, "w+");
        fwrite($handle, $content);
        fclose($handle);

        //Include the file
        extract($this->_data, EXTR_SKIP);

        ob_start();
        include $tempfile;
        $content = ob_get_clean();

        unlink($tempfile);
    }

    /**
     * Process the template
     *
     * This function passes the template through write filter chain and returns the result.
     *
     * @return string  The rendered data
     */
    protected function _process(&$content)
    {
        $context = $this->getCommandContext();

        $context->data = $content;
        $this->getCommandChain()->run(TemplateFilter::MODE_WRITE, $context);
        $content = $context->data;
    }

    /**
     * Returns the template contents
     *
     * @return  string
     * @see getContents()
     */
    public function __toString()
    {
        return $this->getContent();
    }
}