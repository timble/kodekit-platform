<?php
/**
 * @version		$Id: abstract.php 1815 2010-03-27 21:42:55Z johan $
 * @category	Koowa
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Template View Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 * @uses		KMixinClass
 * @uses 		KTemplate
 * @uses 		KFactory
 */
abstract class KViewTemplate extends KViewAbstract
{
	/**
	 * The document object
	 *
	 * @var object
	 */
	protected $_document;
	
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
	protected $_template_path = array();

	/**
	 * The name of the default template source file.
	 *
	 * @var string
	 */
	protected $_template;

	/**
     * Callback for escaping.
     *
     * @var string
     */
    protected $_escape;

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		// assign the document object
		$this->_document = $config->document;

		 // user-defined escaping callback
        $this->setEscape($config->escape);
        
		// Add default template paths
		if(!empty($config->template_path)) {
			$this->addTemplatePath($config->template_path);
		}
		
		// set the layout
		$this->setLayout($config->layout);

		//Register the view stream wrapper
		KTemplate::register();
		KTemplate::addRules($config->template_rules);
	}

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'escape'        => 'htmlspecialchars',
            'layout'        => 'default',
			'template_rules' => array(
                        KFactory::get('lib.koowa.template.filter.shorttag'),
                        KFactory::get('lib.koowa.template.filter.token'),
                        KFactory::get('lib.koowa.template.filter.variable')
						),
            'template_path' => null,
			'document'      => KFactory::get('lib.koowa.document'),
        ));
        
        parent::_initialize($config);
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
	* @return KViewAbstract
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
		}

		// assign by associative array
		elseif (is_array($arg0))
		{
			foreach ($arg0 as $key => $val)
			{
				if (substr($key, 0, 1) != '_') {
					$this->$key = $val;
				}
			}
		}

		// assign by string name and mixed value.
		elseif (is_string($arg0) && substr($arg0, 0, 1) != '_' && func_num_args() > 1)
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
	* @param	string 	The template name.
	* @return 	KViewAbstract
	*/
	public function setLayout($layout)
	{
		$this->_layout = $layout;
		return $this;
	}

	 /**
     * Sets the _escape() callback.
     *
     * @param 	mixed The callback for _escape() to use.
     * @return 	KViewAbstract
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
	 * @return  KViewAbstract
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

			// remove trailing slash
			if (substr($dir, -1) == DIRECTORY_SEPARATOR) {
				$dir = substr_replace($dir, '', -1);
			}

			// add to the top of the search dirs
			array_unshift($this->_template_path, $dir);
		}

		return $this;
	}

	/**
	 * Load a template file -- first look in the templates folder for an override
	 * 
	 * This functions accepts both template local template file names or identifiers
	 * - application::com.component.view.[.path].name
	 *
	 * @param 	string 	The name of the template source file automatically searches
	 * 					the template paths and compiles as needed.
	 * @throws KViewException
	 * @return string The output of the the template script.
	 */
	public function loadTemplate( $identifier = null)
	{
		// Clear prior output
		$this->output = '';
		
		// If no identifier has been specified using the view layout
		$identifier = isset($identifier) ? $identifier : $this->_layout;
		
		try
		{
			$identifier = new KIdentifier($identifier);
			
			$file = $identifier->name;
			$path = dirname(KLoader::path($identifier)).DS.'tmpl';
		} 
		catch(KIdentifierException $e) 
		{
			$file = $identifier;
			$path = dirname($this->_identifier->filepath).DS.'tmpl';
		}
		
		//add the default path to the end of the array
		array_push( $this->_template_path, $path);
		
		// load the template script
		KLoader::load('lib.joomla.filesystem.path');
		$this->_template = $this->findTemplate($this->_template_path, $file.'.php');

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
		$this->output = ob_get_contents();
		ob_end_clean();

		return $this->output;
	}

	/**
	 * Searches the directory paths for a given file.
	 *
	 * @param	array|string	An path or array of path to search in
	 * @param	string			The file name to look for.
	 * @return	mixed			The full path and file name for the target file, or FALSE
	 * 							if the file is not found in any of the paths
	 */
	public function findTemplate($paths, $file)
	{
		settype($paths, 'array'); //force to array

		// start looping through the path set
		foreach ($paths as $path)
		{
			// get the path to the file
			$fullname = $path.DS.$file;

			// is the path based on a stream?
			if (strpos($path, '://') === false)
			{
				// not a stream, so do a realpath() to avoid directory
				// traversal attempts on the local file system.
				$path = realpath($path); // needed for substr() later
				$fullname = realpath($fullname);
			}

			// the substr() check added to make sure that the realpath()
			// results in a directory registered so that
			// non-registered directores are not accessible via directory
			// traversal attempts.
			if (file_exists($fullname) && substr($fullname, 0, strlen($path)) == $path) {
				return $fullname;
			}
		}

		// could not find the file in the set of paths
		return false;
	}

	/**
	 * Execute and return the views output
 	 *
	 * @return 	string
	 */
	public function __toString()
	{
		return $this->loadTemplate();
	}
}