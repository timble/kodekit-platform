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
			$this->_view = $config->view;
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
	 * Get the template path
	 * 
	 * @return	string
	 */
	public function getPath()
	{
		return $this->_path;
	}
	
	/**
	 * Get the view object attached to the template
	 *
	 * @return	KViewAbstract
	 */
	public function getView()
	{
	    if(!$this->_view instanceof KViewAbstract)
		{	   
		    //Make sure we have a view identifier
		    if(!($this->_view instanceof KIdentifier)) {
		        $this->setView($this->_view);
            }
		    
		    $this->_view = KFactory::tmp($this->_view, $config);
		}
		
		return $this->_view;
	}

	/**
	 * Method to set a view object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a view identifier
	 * @return	KControllerAbstract
	 */
	public function setView($view)
	{
		if(!($view instanceof KViewAbstract))
		{
			if(is_string($view) && strpos($view, '.') === false ) 
		    {
			    $identifier			= clone $this->_identifier;
			    $identifier->path	= array('view', $view);
			    $identifier->name	= KRequest::format() ? KRequest::format() : 'html';
			}
			else $identifier = KFactory::identify($view);
		    
			if($identifier->path[0] != 'view') {
				throw new KTemplateException('Identifier: '.$identifier.' is not a view identifier');
			}

			$view = $identifier;
		}
		
		$this->_view = $view;
		
		return $this;
	}
	
	/**
	 * Load a template by identifier
	 * 
	 * This functions only accepts full identifiers of the format
	 * - application::com.component.view.[.path].name
	 *
	 * @param   string 	The template identifier
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @return KTemplateAbstract
	 */
	public function loadIdentifier($template, $data = array())
	{
	    //Identify the template
	    $identifier = KFactory::identify($template);
       
        // Load the identifier
        $file = $identifier->name; 
		
        if($identifier->filepath) {
	       $path = dirname($identifier->filepath);
        } else {
	       $path = dirname(KLoader::path($identifier));
	    }
	   
		$this->loadFile($path.'/'.$file.'.php', $data);
		
		return $this;
	}
	
	/**
	 * Load a template by path
	 *
	 * @param   string 	The template path
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @return KTemplateAbstract
	 */
	public function loadFile($file, $data = array())
	{
		// find the template 
		$path = $this->findFile($file);
	    
		if ($path === false) {
			throw new KTemplateException( 'Template "' . $file . '" not found' );
		}
		
		// get the file contents
		$contents = file_get_contents($path);
		
		// load the contents
		$this->loadString($contents, $data, $file);
		
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
		
		// Merge the data
	    $this->_data = array_merge($this->_data, $data);
	
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
	public function addFilter($filters)
 	{
 		$filters = (array) $filters;
 	    
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
	 * Searches for the file
	 *
	 * @param	string	The file path to look for.
	 * @return	mixed	The full path and file name for the target file, or FALSE
	 * 					if the file is not found
	 */
	public function findFile($file)
	{    
	    $result = false;
	    $path   = dirname($file);
	    
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
	 * Load a template helper
	 * 
	 * This functions accepts a partial identifier, in the form of helper.function. If a partial
	 * identifier is passed a full identifier will be created using the template identifier.
	 *
	 * @param	string	Name of the helper, dot separated including the helper function to call
	 * @param	mixed	Parameters to be passed to the helper
	 * @return 	string	Helper output
	 */
	public function renderHelper($identifier, $params = array())
	{
		//Get the function to call based on the $identifier
		$parts    = explode('.', $identifier);
		$function = array_pop($parts);
		
		//Create the complete identifier if a partial identifier was passed
		if(is_string($identifier) && count($parts) == 1 ) 
		{
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('template','helper');
            $identifier->name = $parts[0];
		} 
		else $identifier = implode('.', $parts);
		
		//Create the template helper
		$helper = KTemplateHelper::factory($identifier);
		
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