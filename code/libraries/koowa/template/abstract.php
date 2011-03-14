<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

 /**
  * Abstract Template class
  * 
  * @author		Johan Janssens <johan@nooku.org>
  * @category	Koowa
  * @package	Koowa_Template
  */
abstract class KTemplateAbstract extends KObject implements KObjectIdentifiable
{ 
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
	protected $_data = array();
	
	/**
	 * The template contents
	 * 
	 * @var string
	 */
	protected $_contents = '';
	
	/**
     * The set of search directories for templates
     *
     * @var array
     */
   	protected $_paths = array();
   	
   	/**
     * The set of template filters for templates
     *
     * @var array
     */
   	protected $_filters = array();
   	
   	/**
	 * View object or identifier (APP::com.COMPONENT.view.NAME.FORMAT)
	 *
	 * @var	string|object
	 */
    protected $_view;
    	
	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the contructor private
	 * 
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		// Set the table indentifier
    	if(isset($config->view)) {
			$this->setView($config->view);
		}
			
		//Register the template stream wrapper
		KTemplateStream::register();
		
		//Set the template search paths
		$this->_paths = KConfig::toData($config->paths);
		
		 // Mixin a command chain
        $this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));
	}
	
 	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'view '				=> null,
    		'paths'				=> array(),
            'command_chain' 	=> new KCommandChain(),
    		'dispatch_events'   => false,
    		'enable_callbacks' 	=> false,
        ));
        
        parent::_initialize($config);
    }
    
	/**
	 * Get the object identifier
	 * 
	 * @return	KIdentifier	
	 * @see 	KObjectIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}
	
	/**
	 * Get the identifier for the view with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	public function getView()
	{
		if(!$this->_view)
		{
			$identifier	= clone $this->_identifier;
			$identifier->path = array('view', $identifier->name);
			$identifier->name = 'html';
			
			$this->_view = KFactory::get($identifier);
		}
		
		return $this->_view;
	}
	
	/**
	 * Method to set a view object attached to the template
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a table identifier
	 * @return	KTemplateAbstract
	 */
	public function setView($view)
	{
		if(!($view instanceof KViewAbstract))
		{
			$identifier = KFactory::identify($view);
		
			if($identifier->path[0] != 'view') {
				throw new KViewException('Identifier: '.$identifier.' is not a view identifier');
			}
		
			$view = KFactory::get($identifier);
		}
		
		$this->_view = $view;
		return $this;
	}
	
	/**
	 * Load a template by identifier -- first look in the templates folder for an override
	 * 
	 * This functions accepts both template local template file names or identifiers
	 * - application::com.component.view.[.path].name
	 *
	 * @param   string 	The template identifier
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @return KTemplateAbstract
	 */
	public function loadIdentifier($identifier, $data = array())
	{
		try
		{
			$identifier = new KIdentifier($identifier);
			
			$file = $identifier->name;
			$path = dirname(KLoader::path($identifier)).'/tmpl';
		} 
		catch(KIdentifierException $e) 
		{
			$file = $identifier;
			$path = dirname($this->getView()->getIdentifier()->filepath).'/tmpl';
		}
		
		// load the path
		$this->loadPath($path.'/'.$file.'.php', $data);
		
		return $this;
	}
	
	/**
	 * Load a template by path -- first look in the templates folder for an override
	 * 
	 * The name of the template source file automatically searches the template paths in LIFO
	 * order.
	 *
	 * @param   string 	The template path
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @return KTemplateAbstract
	 */
	public function loadPath($path, $data = array())
	{
		//add the default path to the end of the array
		array_push( $this->_paths, dirname($path));
		
		// find the template 
		$template = $this->findPath(basename($path));
		
		if ($template === false) {
			throw new KTemplateException( 'Template "' . $path . '" not found' );
		}
		
		// get the file contents
		$contents = file_get_contents($template);
		
		// load the contents
		$this->loadString($contents, $data, $template);
		
		return $this;
	}
	
	/**
	 * Load a template from a string
	 *
	 * @param   string 	The template contents
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @param	string	The template path. If empty the path will be calculated based on the template contents.
	 * @return KTemplateAbstract
	 */
	public function loadString($string, $data = array(), $path = '')
	{
		$this->_contents = $string;
		$this->_path     = empty($path) ? md5($string) : $path;
		
		// set the data
		if(!empty($data)) {
			$this->_data = $data;
		}
		
		return $this;
	}
	
	/**
	 * Implement a sandbox to load and render a template
	 * 
	 * This function passes the template through the read filter chain and then include 
	 * it in local scope buffers the result and passes it through the write filter chain 
	 * before returning.
	 *
	 * @param  boolean 	If TRUE apply write filters. Default FALSE.
	 * @return KTemplateAbstract
	 */
	public function render($filter = false)
	{	
		//Set the template in the template registry
       	KFactory::get('lib.koowa.template.registry')->set($this->_path, $this);
       	
       	extract($this->_data, EXTR_SKIP); //extract the data in local scope
       	
       	// Capturing output into a buffer
		ob_start();
		include 'tmpl://'.$this->_path;
		$this->_contents = ob_get_clean();
		
		if($filter) {
			$this->_contents = $this->filter(KTemplateFilter::MODE_WRITE);
		}
	
		//Remove the template object from the template registry
       	KFactory::get('lib.koowa.template.registry')->del($this->_path);
       
		return $this->_contents;
	}

	/**
	 * Pass the data through the filter chain and perform
	 *
	 * @param string	The filter mode
	 * @return string	The filtered data
	 */
	public function filter($mode = KTemplateFilter::MODE_READ)
	{	
        $context = $this->getCommandContext();
		$context->data = $this->_contents;
				
        $result = $this->getCommandChain()->run($mode, $context);
 		
 		return $context->data;
	}
	
	/**
	 * Adds one or multiple filters for template transformation
	 * 
	 * @param array 	Array of one or more behaviors to add.
	 * @return KTemplate
	 */
	public function addFilters($filters)
 	{
 		foreach($filters as $filter)
		{
			if(!($filter instanceof KTemplateFilterInterface)) 
			{
				$identifier = (string) $filter;
				$filter     = KTemplateFilter::factory($filter);
			}
			else $identifier = (string) $filter->getIdentifier();
				
			//Enqueue the filter in the command chain
			$this->getCommandChain()->enqueue($filter);
			
			//Store the filter
			$this->_filters[$identifier] = $filter;
		}
		
		return $this;
 	}
 	
 	/**
	 * Get the filters for the template
	 *
	 * @return array	An asscociate array of filters. The keys are the filter identifiers.
	 */
 	public function getFilters()
 	{
 		return $this->_filters;
 	}
 	
	/**
	 * Get a filter by identifier
	 *
	 * @return array	An asscociate array of filters keys are the filter identifiers
	 */
 	public function getFilter($identifier)
 	{
 		return isset($this->_filters[$identifier]) ? $this->_filters[$identifier] : null;
 	}
 	
	/**
	 * Get a list of template paths
	 *
	 * @return  array	An array of template paths
	 */
	public function getPaths($paths)
	{
		return $this->_paths;
	}
 	
	/**
	 * Remove one or more template path(s) from the stack
	 *
	 * @param string|array The path(s) to remove.
	 * @return  KTemplateAbstract
	 */
	public function removePath($paths)
	{
		// just force to array
		settype($paths, 'array');

		// loop through the path directories
		foreach ($paths as $path)
		{
			// no surrounding spaces allowed!
			$path = trim($path);

			// remove trailing slash
			if (substr($path, -1) == DIRECTORY_SEPARATOR) {
				$path = substr_replace($path, '', -1);
			}
			
			// remove the path from the 
			if($key = array_search($this->_paths, $path)) {
				unset($this->_paths[$key]);
			}
		}

		return $this;
	}
 	
	/**
	 * Adds to the stack of template paths
	 * 
	 * If a duplicate path is added to the stack the first path in the stack 
	 * will be kept all others are removed.
	 *
	 * @param string|array The path(s) to add.
	 * @return  KTemplateAbstract
	 */
	public function addPath($paths, $append = false)
	{
		// just force to array
		if(is_string($paths)) {
		    settype($paths, 'array');
		}

		// loop through the paths
		foreach ($paths as $path)
		{
			// no surrounding spaces allowed!
			$path = trim($path);

			// remove trailing slash
			if (substr($path, -1) == DIRECTORY_SEPARATOR) {
				$dir = substr_replace($path, '', -1);
			}

			// add to the top of the search dirs
			if(!$append) {
				array_unshift( $this->_paths, $path);
			} else {
				array_push( $this->_paths, $path);
			}
		}
		
		//Filter out any duplicate values
		$this->_paths = array_unique($this->_paths);

		return $this;
	}
	
	/**
	 * Searches the directory paths for a given template file.
	 *
	 * @param	string			The file name to look for.
	 * @return	mixed			The full path and file name for the target file, or FALSE
	 * 							if the file is not found in any of the paths
	 */
	public function findPath($file)
	{
		settype($paths, 'array'); //force to array

		// start looping through the path set
		foreach ($this->_paths as $path)
		{
			// get the path to the file
			$fullname = $path.'/'.$file;

			// is the path based on a stream?
			if (strpos($path, '://') === false)
			{
				// not a stream, so do a realpath() to avoid directory
				// traversal attempts on the local file system.
				$path 	  = realpath($path); // needed for substr() later
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
	 * Load a template helper
	 *
	 * @param	string	Name of the helper, dot separated including the helper function to call
	 * @param	mixed	Parameters to be passed to the helper
	 * @return 	string	Helper output
	 */
	public function loadHelper($identifier, $params = array())
	{
		//Get the function to call based on the $identifier
		$parts    = explode('.', $identifier);
		$function = array_pop($parts);
		
		//Create the template helper
		$helper = KTemplateHelper::factory(implode('.', $parts));
		
		//Call the helper function
		if (!is_callable( array( $helper, $function ) )) {
			throw new KTemplateHelperException( get_class($helper).'::'.$function.' not supported.' );
		}	
		
		return $helper->$function($params);
	}
	
	/**
	 * Renders the template and returns the result
 	 *
	 * @return 	string
	 */
	public function __toString()
	{
		try {
			$result = $this->render();
		} catch (Exception $e) {
			$result = $e->getMessage();
		} 
			
		return $result;
	}
}