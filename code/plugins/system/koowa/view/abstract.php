<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract View Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 * @uses		KMixinClass
 * @uses 		KTemplate
 * @uses 		KFactory
 */
abstract class KViewAbstract extends KObject implements KFactoryIdentifiable
{
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
	 * The object identifier
	 *
	 * @var KIdentifierInterface
	 */
	protected $_identifier;
	
	/**
	 * Model identifier (APP::com.COMPONENT.model.MODELNAME)
	 *
	 * @var	string|object
	 */
	protected $_model;

	/**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
		// Allow the identifier to be used in the initalise function
        $this->_identifier = $options['identifier'];

		// Initialize the options
        $options  = $this->_initialize($options);

		 // user-defined escaping callback
        $this->setEscape($options['escape']);
        
		// Add default template paths
		if($options['template_path']) {
			$this->addTemplatePath($options['template_path']);
		}

		// assign the document object
		$this->_document = $options['document'];
		
		// set the model
		if(isset($options['model'])) {
			$this->setModel($options['model']);
		}

		// set the layout
		$this->setLayout($options['layout']);

		//Register the view stream wrapper
		KTemplate::register();
		KTemplate::addRules($options['template_rules']);
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
            'base_url'      => KRequest::base(),
        	'media_url'		=> KRequest::root().'/media',
            'document'      => KFactory::get('lib.koowa.document'),
            'escape'        => 'htmlspecialchars',
            'layout'        => 'default',
			'template_rules' => array(
                        KFactory::get('lib.koowa.template.filter.shorttag'),
                        KFactory::get('lib.koowa.template.filter.token'),
                        KFactory::get('lib.koowa.template.filter.variable')
						),
            'template_path' => null,
			'identifier'	=> null,
			'model'   		=> null
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Get the identifier
	 *
	 * @return 	KIdentifierInterface
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}
	
	/**
	 * Get the name
	 *
	 * @return 	string 	The name of the object
	 */
	public function getName()
	{
		$total = count($this->_identifier->path);
		return $this->_identifier->path[$total - 1];
	}

	/**
	 * Renders and echo's the views output
 	 *
	 * @return KViewAbstract
	 */
	abstract public function display();

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
	 * Get the identifier for the model with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getModel()
	{
		if(!$this->_model)
		{
			$identifier	= clone $this->_identifier;
			$name = array_pop($identifier->path);
			$identifier->name	= KInflector::isPlural($name) ? $name : KInflector::pluralize($name);
			$identifier->path	= array('model');
			
			$this->_model = $identifier;
		}
       	
		return $this->_model;
	}
	
	/**
	 * Method to set a model object attached to the view
	 *
	 * @param	mixed	An object that implements KFactoryIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a table identifier
	 * @return	KViewAbstract
	 */
	public function setModel($model)
	{
		$identifier = KFactory::identify($model);
		
		if($identifier->path[0] != 'model') {
			throw new KModelException('Identifier: '.$identifier.' is not a model identifier');
		}
		
		$this->_model = $identifier;
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
		$this->_output = null;
		
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
		$this->_output = ob_get_contents();
		ob_end_clean();

		return $this->_output;
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
	 * Create a route based on a full or partial query string 
	 * 
	 * index.php, option, view and layout can be ommitted. The following variations 
	 * will all result in the same route
	 *
	 * - foo=bar
	 * - option=com_mycomp&view=myview&foo=bar
	 * - index.php?option=com_mycomp&view=myview&foo=bar
	 * 
	 * If the route starts '&' the information will be appended to the current URL.
	 *
	 * In templates, use @route()
	 *
	 * @param	string	The query string used to create the route
	 * @return 	string 	The route
	 */
	public function createRoute( $route = '')
	{
		$route = trim($route);

		// Special cases
		if($route == 'index.php' || $route == 'index.php?') 
		{
			$result = $route;
		} 
		else if (substr($route, 0, 1) == '&') 
		{
			$url   = clone KRequest::url();
			$vars  = array();
			parse_str($route, $vars);
			
			$result = (string) $url->setQuery(array_merge($url->getQuery(true), $vars));;
		}
		else 
		{
			// Strip 'index.php?'
			if(substr($route, 0, 10) == 'index.php?') {
				$route = substr($route, 10);
			}

			// Parse route
			$parts = array();
			parse_str($route, $parts);
			$result = array();

			// Check to see if there is component information in the route if not add it
			if(!isset($parts['option'])) {
				$result[] = 'option=com_'.$this->_identifier->package;
			}

			// Add the layout information to the route only if it's not 'default'
			if(!isset($parts['view']))
			{
				$result[] = 'view='.$this->getName();
				if(!isset($parts['layout']) && $this->_layout != 'default') {
					$result[] = 'layout='.$this->_layout;
				}
			}
			
			// Add the format information to the URL only if it's not 'html'
			if(!isset($parts['format']) && $this->_identifier->name != 'html') {
				$result[] = 'format='.$this->_identifier->name;
			}

			// Reconstruct the route
			if(!empty($route)) {
				$result[] = $route;
			}

			$result = 'index.php?'.implode('&', $result);
			
		}

		return JRoute::_($result);
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