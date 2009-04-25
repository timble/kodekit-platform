<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract View Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_View
 * @uses		KMixinClass
 * @uses 		KTemplateDefault
 * @uses 		KFactory
 */
abstract class KViewAbstract extends KObject
{
	/**
	 * The base path of the view
	 *
	 * @var		string
	 */
	protected $_basePath;

	/**
	 * Layout name
	 *
	 * @var		string
	 */
	protected $_layout = 'default';

	/**
	 * The set of search directories for templatex
	 *
	 * @var array
	 */
	protected $_templatePath = array();

	/**
	 * The name of the default template source file.
	 *
	 * @var string
	 */
	protected $_template;

	/**
	 * The output of the template script.
	 *
	 * @var string
	 */
	protected $_output = null;

	/**
     * Callback for escaping.
     *
     * @var string
     */
    protected $_escape;
    
    /**
	 * The document object
	 *
	 * @var object
	 */
	protected $_document;

	/**
	 * Constructor
	 * 
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{		
		// Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KMixinClass
        $this->mixin(new KMixinClass($this, 'View'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		 // set the charset (used by the variable escaping functions)
        $this->_charset = $options['charset'];

		 // user-defined escaping callback
        $this->setEscape($options['escape']);

		// Set a base path for use by the view
		$this->_basePath	= $options['base_path'];

		// set the default template search path
		if ($options['template_path']) {
			// user-defined dirs
			$this->setTemplatePath($options['template_path']);
		} else {
			$this->setTemplatePath($this->_basePath.DS.'tmpl');
		}
		
		// assign the document object
		if ($options['document']) {
			$this->_document = $options['document'];
		} else {
			$this->_document = KFactory::get('lib.joomla.document');
		}

		// set the layout
		$this->setLayout($options['layout']);

		//Register the view stream wrapper
		KTemplateDefault::register();
		KTemplateDefault::addRules($options['template_rules']);

		//Add the include paths for the helpers
		KViewHelper::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_'.$this->getClassName('prefix').DS.'helpers');
	}

    /**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(array $options)
    {
        $defaults = array(
            'base_path'     => JPATH_COMPONENT.DS.'views',
            'base_url'      => JURI::base(true),
            'charset'       => null, // TODO unused?
            'document'      => null,
            'escape'        => 'htmlspecialchars',
            'layout'        => 'default',
            'name'          => array(
                        'prefix'    => 'k',
                        'base'      => 'view',
                        'suffix'    => 'default'
                        ),
			'template_rules' => array( 
                        KFactory::get('lib.koowa.template.rule.shorttag'),
                        KFactory::get('lib.koowa.template.rule.token'),
                        KFactory::get('lib.koowa.template.rule.variable')
						),
            'template_path' => null
        );

        return array_merge($defaults, $options);
    }

	/**
	* Execute and display a template script.
	*
	* @param 	string $tpl The name of the template file to parse
	* @return 	this
	*/
	public function display($tpl = null)
	{
		$result = $this->loadTemplate($tpl);
		echo $result;
		return $this;
	}

	/**
	* Assigns variables to the view script via differing strategies.
	*
	* This method is overloaded; you can assign all the properties of
	* an object, an associative array, or a single value by name.
	*
	* You are not allowed to set variables that begin with an underscore;
	* these are either private properties for KView or private variables
	* within the template script itself.
	*
	* <code>
	* $view = new KViewDefault();
	*
	* // assign directly
	* $view->var1 = 'something';
	* $view->var2 = 'else';
	*
	* // assign by name and value
	* $view->assign('var1', 'something');
	* $view->assign('var2', 'else');
	*
	* // assign by assoc-array
	* $ary = array('var1' => 'something', 'var2' => 'else');
	* $view->assign($obj);
	*
	* // assign by object
	* $obj = new stdClass;
	* $obj->var1 = 'something';
	* $obj->var2 = 'else';
	* $view->assign($obj);
	*
	* </code>
	*
	* @return object KViewAbstract
	*/
	public function assign()
	{
		// get the arguments; there may be 1 or 2.
		$arg0 = @func_get_arg(0);
		$arg1 = @func_get_arg(1);

		// assign by object
		if (is_object($arg0))
		{
			// assign public properties
			foreach (get_object_vars($arg0) as $key => $val)
			{
				if (substr($key, 0, 1) != '_') {
					$this->$key = $val;
				}
			}
			return $this;
		}

		// assign by associative array
		if (is_array($arg0))
		{
			foreach ($arg0 as $key => $val)
			{
				if (substr($key, 0, 1) != '_') {
					$this->$key = $val;
				}
			}
			return $this;
		}

		// assign by string name and mixed value.
		if (is_string($arg0) && substr($arg0, 0, 1) != '_' && func_num_args() > 1) 
		{
			$this->$arg0 = $arg1;
		}

		return $this;
	}

	/**
     * Escapes a value for output in a view script.
     *
     * @param  mixed $var The output to escape.
     * @return mixed The escaped value.
     */
    public function escape($var)
    {
        return call_user_func($this->_escape, $var);
    }

	/**
	* Get the layout.
	*
	* @return string The layout name
	*/

	public function getLayout()
	{
		return $this->_layout;
	}
	
   /**
	* Sets the layout name to use
	*
	* @param	string 	$template The template name.
	* @return 	object 	KViewAbstract
	*/
	public function setLayout($layout)
	{
		$this->_layout = $layout;
		return $this;
	}

	 /**
     * Sets the _escape() callback.
     *
     * @param 	mixed 	$spec The callback for _escape() to use.
     * @return 	object 	KViewAbstract
     */
    public function setEscape($spec)
    {
        $this->_escape = $spec;
        return $this;
    }
	
	/**
	 * Adds to the stack of view script paths in LIFO order.
	 *
	 * @param string|array The directory (-ies) to add.
	 * @return object KViewAbstract
	 */
	public function addTemplatePath($path)
	{
		// just force to array
		settype($path, 'array');

		// loop through the path directories
		foreach ($path as $dir)
		{
			// no surrounding spaces allowed!
			$dir = trim($dir);

			// add trailing separators as needed
			if (substr($dir, -1) != DIRECTORY_SEPARATOR) {
				// directory
				$dir .= DIRECTORY_SEPARATOR;
			}

			// add to the top of the search dirs
			array_unshift($this->_templatePath, $dir);
		}
		
		return $this;
	}
	
	/**
	 * Sets an entire array of search paths for templates or resources.
	 *
	 * @param string 	   $type The type of path to set, typically 'template'.
	 * @param string|array $path The new set of search paths.  If null or
	 * 							 false, resets to the current directory only.
	 * @return object KViewAbstract
	 */
	public function setTemplatePath($path)
	{
		// clear out the prior search dirs
		$this->_templatePath = array();

		// actually add the user-specified directories
		$this->addTemplatePath($path);

		// always add the fallback directories as last resort
		$app = KFactory::get('lib.joomla.application');
		
		// validating option as a command, but sanitizing it to use as a filename
		$option = KRequest::get('get.option', 'cmd', 'filename');
				
		// set the alternative template search dir
		$fallback = JPATH_BASE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.$option.DS.$this->getClassName('suffix');
		$this->addTemplatePath($fallback);
		
		return $this;
	}

	/**
	 * Adds to the stack of helper script paths in LIFO order.
	 *
	 * This function overrides the default view behavior and the path
	 * to the KViewHelper include paths
	 *
	 * @param string|array The directory (-ies) to add.
	 * @return object KViewAbstract
	 */
	public function addHelperPath($path)
	{
		KViewHelper::addIncludePath($path);
		return $this;
	}

	/**
	 * Load a template file -- first look in the templates folder for an override
	 *
	 * @param string $tpl The name of the template source file ...
	 * automatically searches the template paths and compiles as needed.
	 * @throws KViewException
	 * @return string The output of the the template script.
	 */
	public function loadTemplate( $tpl = null)
	{
		// clear prior output
		$this->_output = null;

		//create the template file name based on the layout
		$file = isset($tpl) ? $this->_layout.'_'.$tpl : $this->_layout;

		// load the template script
		Koowa::import('lib.joomla.filesystem.path');
		$this->_template = JPath::find($this->_templatePath, $file.'.php');
		
		if ($this->_template === false) {
			throw new KViewException( 'Layout "' . $file . '" not found' );
		}
			
		// unset so as not to introduce into template scope
		unset($tpl);
		unset($file);

		// never allow a 'this' property
		if (isset($this->this)) {
			unset($this->this);
		}

		// start capturing output into a buffer
		ob_start();
		// include the requested template filename in the local scope
		// (this will execute the view logic).
		include 'tmpl://'.$this->_template;

		// done with the requested template; get the buffer and
		// clear it.
		$this->_output = ob_get_contents();
		ob_end_clean();

		return $this->_output;		
	}

	/**
	 * Load a helper and pass the arguments
	 * 
	 * Alias for KViewHelper::_(). In templates, use @helper()
	 *
	 * @param	string	Name of the helper, dot separated
	 * @param	mixed	Parameters to be passed to the helper
	 * @return 	string	Helper output
	 * 
	 * @see		KViewHelper::_()
	 */
	public function loadHelper( $type)
	{
		$args = func_get_args();
		return call_user_func_array(array('KViewHelper', '_'), $args );
	}
	
	/**
	 * Create a route. Index.php, option, view and layout can be ommitted. The 
	 * following variations will all result in the same route
	 * foo=bar
	 * option=com_mycomp&view=myview&foo=bar
	 * index.php?option=com_mycomp&view=myview&foo=bar
	 * In templates, use @route()
	 *
	 * @param	string	The data to use to create the route
	 * @return 	string 	The route
	 */
	public function createRoute( $route = '')
	{
		$route = trim($route);
		
		// special cases
		if($route == 'index.php' || $route == 'index.php?' || empty($route)) {
			return JRoute::_($route);
		}
		
		// strip 'index.php?' 
		if(substr($route, 0, 10)=='index.php?') {
			$route = substr($route, 10);
		}
		
		// parse
		$parts = array();
		parse_str($route, $parts);
		$result = array();
		
		// Check to see if there is component information in the route if not add it
		if(!isset($parts['option'])) {
			$result[] = 'option=com_'.$this->getClassName('prefix');
		}

		// Check to see if there is view information in the route if not add it
		if(!isset($parts['view'])) 
		{
			$result[] = 'view='.$this->getClassName('suffix');
			if(!isset($parts['layout']) && $this->_layout != 'default') {
				$result[] = 'layout='.$this->_layout;
			}
		}
		
		// Reconstruct the route
		$result[] = $route;
		$result = implode('&', $result);
		return JRoute::_('index.php?'.$result);
	}
}
