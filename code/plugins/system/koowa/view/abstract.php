<?php
/**
 * @version		$Id$
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract View Class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package		Koowa_View
 * @uses		KPatternClass
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
	* The set of search directories for resources (templates)
	*
	* @var array
	*/
	protected $_path = array(
		'template' => array(),
	);

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
	 * Constructor
	 */
	public function __construct($options = array())
	{
        // Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KPatternClass
        $this->mixin(new KPatternClass($this, 'Controller'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		 // set the charset (used by the variable escaping functions)
        $this->_charset = $options['charset'];

		 // user-defined escaping callback
        $this->setEscape($options['escape']);

		// Set a base path for use by the view
		if ($options['base_path']) {
			$this->_basePath	= $options['base_path'];
		} else {
			$this->_basePath	= JPATH_COMPONENT.DS.'views'.DS.$this->getClassName('suffix');
		}

		// Set a base path for use by the view
		$this->assign('baseurl',	$options['base_url']);

		// set the default template search path
		if ($options['template_path']) {
			// user-defined dirs
			$this->_setPath('template', $options['template_path']);
		} else {
			$this->_setPath('template', $this->_basePath.DS.'tmpl');
		}

		// set the layout
		$this->setLayout($options['layout']);

		// assign the document object
		if ($options['document']) {
			$this->assignRef('document', $options['document']);
		} else {
			$this->assignRef('document', KFactory::get('Document'));
		}

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
    protected function _initialize($options)
    {
        $defaults = array(
            'base_path'     => JPATH_COMPONENT,
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
						'@template' => '@loadTemplate',
						'@text'	    => 'JText::_',
						'@helper'   => '@loadHelper',
						'@route'    => 'JRoute::_'
						),
            'template_path' => null
        );

        return array_merge($defaults, $options);
    }

	/**
	* Execute and display a template script.
	*
	* @param string $tpl The name of the template file to parse;
	* automatically searches through the template paths.
	*
	* @throws object An JError object.
	* @see fetch()
	*/
	public function display($tpl = null)
	{
		$result = $this->loadTemplate($tpl);
		if (JError::isError($result)) {
			return $result;
		}

		echo $result;
	}

	/**
	* Assigns variables to the view script via differing strategies.
	*
	* This method is overloaded; you can assign all the properties of
	* an object, an associative array, or a single value by name.
	*
	* You are not allowed to set variables that begin with an underscore;
	* these are either private properties for JView or private variables
	* within the template script itself.
	*
	* <code>
	* $view = new JView();
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
	* @return bool True on success, false on failure.
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
			return true;
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
			return true;
		}

		// assign by string name and mixed value.

		// we use array_key_exists() instead of isset() becuase isset()
		// fails if the value is set to null.
		if (is_string($arg0) && substr($arg0, 0, 1) != '_' && func_num_args() > 1)
		{
			$this->$arg0 = $arg1;
			return true;
		}

		// $arg0 was not object, array, or string.
		return false;
	}


	/**
	* Assign variable for the view (by reference).
	*
	* You are not allowed to set variables that begin with an underscore;
	* these are either private properties for KView or private variables
	* within the template script itself.
	*
	* <code>
	* $view = new KView();
	*
	* // assign by name and value
	* $view->assignRef('var1', $ref);
	*
	* // assign directly
	* $view->ref =& $var1;
	* </code>
	*
	* @param string $key The name for the reference in the view.
	* @param mixed &$val The referenced variable.
	*
	* @return bool True on success, false on failure.
	*/

	public function assignRef($key, &$val)
	{
		if (is_string($key) && substr($key, 0, 1) != '_')
		{
			$this->$key =& $val;
			return true;
		}

		return false;
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
	 * Method to get the model object
	 *
	 * @param	string	$name	The name of the model (optional)
	 * @return	mixed			Model object
	 */
	public function getModel( $name = null )
	{
		if ($name === null) {
			$name = $this->_defaultModel;
		}
		return $this->_models[strtolower( $name )];
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
	 * Method to add a model to the view.  We support a multiple model single
	 * view system by which models are referenced by classname.  A caveat to the
	 * classname referencing is that any classname prepended by KModel will be
	 * referenced by the name without KModel, eg. KModelCategory is just
	 * Category.
	 *
	 * @param	object	$model		The model to add to the view.
	 * @param	boolean	$default	Is this the default model?
	 * @return	object				The added model
	 */
	public function setModel( &$model, $default = false )
	{
		$name = strtolower($model->getClassName('suffix'));
		$this->_models[$name] = &$model;

		if ($default) {
			$this->_defaultModel = $name;
		}
		return $model;
	}

   /**
	* Sets the layout name to use
	*
	* @param	string $template The template name.
	* @return	string Previous value
	*/
	public function setLayout($layout)
	{
		$previous		= $this->_layout;
		$this->_layout = $layout;
		return $previous;
	}

	 /**
     * Sets the _escape() callback.
     *
     * @param mixed $spec The callback for _escape() to use.
     */
    public function setEscape($spec)
    {
        $this->_escape = $spec;
    }
	
	/**
	 * Get the filename for a resource.
	 *
	 * @param	array	An associative array of filename information. Optional.
	 * @return	string	The filename.
	 */
	public static function getFileName( $parts = array() )
	{
		//Get the document type
		$type   = KFactory::get('Document')->getType();

		$filename = strtolower($parts['name']).DS.$type.'.php';
		return $filename;
	}

	/**
	 * Adds to the stack of view script paths in LIFO order.
	 *
	 * @param string|array The directory (-ies) to add.
	 * @return void
	 */
	public function addTemplatePath($path)
	{
		$this->_addPath('template', $path);
	}

	/**
	 * Adds to the stack of helper script paths in LIFO order.
	 *
	 * This function overrides the default view behavior and the path
	 * to the KViewHelper include paths
	 *
	 * @param string|array The directory (-ies) to add.
	 * @return void
	 */
	public function addHelperPath($path)
	{
		KViewHelper::addIncludePath($path);
	}

	/**
	 * Load a template file -- first look in the templates folder for an override
	 *
	 * @param string $tpl The name of the template source file ...
	 * automatically searches the template paths and compiles as needed.
	 * @return string The output of the the template script.
	 */
	public function loadTemplate( $tpl = null)
	{
		global $mainframe, $option;

		// clear prior output
		$this->_output = null;

		// clean the file name
		//$tpl  = preg_replace('/[^A-Z0-9_\.-]/i', '', $tpl);

		//create the template file name based on the layout
		$file = isset($tpl) ? $this->_layout.'_'.$tpl : $this->_layout;

		// load the template script
		Koowa::import('joomla.filesystem.path');
		$this->_template = JPath::find($this->_path['template'], $file.'.php');
		
		if ($this->_template != false)
		{
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
		else {
			return JError::raiseError( 500, 'Layout "' . $file . '" not found' );
		}
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
	* Sets an entire array of search paths for templates or resources.
	*
	* @param string $type The type of path to set, typically 'template'.
	* @param string|array $path The new set of search paths.  If null or
	* false, resets to the current directory only.
	*/
	protected function _setPath($type, $path)
	{
		global $mainframe, $option;

		// clear out the prior search dirs
		$this->_path[$type] = array();

		// actually add the user-specified directories
		$this->_addPath($type, $path);

		// always add the fallback directories as last resort
		switch (strtolower($type))
		{
			case 'template':
			{
				// set the alternative template search dir
				if (isset($mainframe))
				{
					$option = preg_replace('/[^A-Z0-9_\.-]/i', '', $option);
					$fallback = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$option.DS.$this->getClassName('suffix');
					$this->_addPath('template', $fallback);
				}
			}	break;
		}
	}

	/**
	* Adds to the search path for templates and resources.
	*
	* @param string|array $path The directory or stream to search.
	*/
	protected function _addPath($type, $path)
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
			array_unshift($this->_path[$type], $dir);
		}
	}
}
