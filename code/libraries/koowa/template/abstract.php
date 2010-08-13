<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

 /**
  * Abstract Template class
  * 
  * @author		Johan Janssens <johan@koowa.org>
  * @category	Koowa
  * @package	Koowa_Template
  */
abstract class KTemplateAbstract extends KObject implements KObjectIdentifiable
{ 
	/**
     * The set of search directories for templates
     *
     * @var array
     */
   	protected $_path = array();
   	
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
		
		//Set the paths
		$this->_path = $config->path;
		
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
    		'path'				=> array(),
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
			
			$this->_view = $identifier;
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
		$identifier = KFactory::identify($view);
		
		if($identifier->path[0] != 'view') {
			throw new KViewException('Identifier: '.$identifier.' is not a view identifier');
		}
		
		$this->_view = $identifier;
		return $this;
	}
	
	/**
	 * Find a template file and render it
	 * 
	 * The name of the template source file automatically searches the template paths in LIFO
	 * order.
	 *
	 * @param 	string 	The template file to load
	 * @param 	array	An associative array of data to be extracted in local template scope
	 * @throws KTemplateException
	 * @return string The output of the the template script.
	 */
	public function find( $path, $data)
	{
		//add the default path to the end of the array
		array_push( $this->_path, dirname($path));
		
		// load the template script
		$template = $this->findPath(basename($path));

		if ($template === false) {
			throw new KTemplateException( 'Template "' . $path . '" not found' );
		}
		
		//Render the template
		$result = $this->render($template, $data);

		return $result;
	}
	
	/**
	 * Implement a sandbox to load and render a template
	 * 
	 * This function loads the template file, passes it through the read filter chain
	 * and then include it in local scope buffers the result and passes it through the 
	 * write filter chain before returning.
	 *
	 * @param	string 	The template file to load
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @return string
	 */
	public function render($path, $data)
	{	
		extract($data, EXTR_SKIP); //extract the data in local scope
       	
       	//Set the template in the template registry
       	KFactory::get('lib.koowa.template.registry')->set($path, $this);

       	// Capturing output into a buffer
		ob_start();
		include 'tmpl://'.$path;
		$output = ob_get_contents();
		ob_end_clean();
	
		//Filter the data before writing
		$output = $this->filter($output, KTemplateFilter::MODE_WRITE);
		
		//Remove the template object from the template registry
       	KFactory::get('lib.koowa.template.registry')->del($path);
		
		return $output;
	}

	/**
	 * Pass the data through the filter chain and perform
	 *
	 * @param string	The data to filter
	 * @param string	The filter mode
	 * @return string	The filtered data
	 */
	public function filter($data, $mode = KTemplateFilter::MODE_READ)
	{	
        $context = $this->getCommandContext();
		$context->data	  	= $data;
				
        $result = $this->getCommandChain()->run($mode, $context);
 		
 		return $context->data;
	}
	
	/**
	 * Adds one or multiple filters for template transformation
	 * 
	 * @param array 	Array of one or more behaviors to add.
	 * @return KTemplate
	 */
	public function addFilters(array $filters)
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
			$this->getCommandChain()->enqueue($filter, $filter->getPriority());
			
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
	 * Adds to the stack of view script paths
	 *
	 * @param string|array The directory (-ies) to add.
	 * @return  KTemplateAbstract
	 */
	public function addPath($path, $append = false)
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
			if(!$append) {
				array_unshift( $this->_path, $dir);
			} else {
				array_push( $this->_path, $dir);
			}
		}

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
		foreach ($this->_path as $path)
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
	 * This function merges the elements of the attached view model state with the parameters passed to the helper
	 * so that the values of one are appended to the end of the previous one. 
	 * 
	 * If the view state have the same string keys, then the parameter value for that key will overwrite the state.
	 *
	 * @param	string	Name of the helper, dot separated including the helper function to call
	 * @param	mixed	Parameters to be passed to the helper
	 * @return 	string	Helper output
	 */
	public function loadHelper($identifier, $params = array())
	{
		$view = KFactory::get($this->getView());
		
		if($state = KFactory::get($view->getModel())->getState()) {
			$params = array_merge($state->getData(), $params);
		}
		
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
}