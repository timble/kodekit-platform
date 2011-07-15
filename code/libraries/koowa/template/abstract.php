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
	 * The template stack object
	 *
	 * @var	KTemplateStack
	 */
    protected $_stack;
    
    /**
     * Template errors
     *
     * @var array
     */  
    private static $_errors = array(
        1     => 'Fatal Error',
        2     => 'Warning',
        4     => 'Parse Error',
        8     => 'Notice',
        256   => 'User Error',
        512   => 'User Warning',
        2048  => 'Strict',
        4096  => 'Recoverable Error'
    );
    	
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
	
		// Set the view indentifier
    	$this->_view = $config->view;
		
    	// Set the template stack object
		$this->_stack = $config->stack;
			
		//Register the template stream wrapper
		KTemplateStream::register();
		
		//Set shutdown function to handle sandbox errors
        register_shutdown_function(array($this, '__destroy')); 
		
		 // Mixin a command chain
        $this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));
	}
	
	/**
     * Destructor
     * 
     * Hanlde sandbox shutdown. Clean all output buffers and display the latest error
     * if an error is found. 
     * 
     * @return bool
     */
	public function __destroy()
	{
	    if(!$this->getStack()->isEmpty())
	    {
	        if($error = error_get_last()) 
            {
                if($error['type'] === E_ERROR || $error['type'] === E_PARSE) 
                {  
                    while(@ob_get_clean());
                    $this->sandboxError($error['type'], $error['message'], $error['file'], $error['line']);
                }
            }
	    }
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
    		'stack'				=> KFactory::get('lib.koowa.template.stack'),
    		'view'				=> null,
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
	 * Get the template stack object
 	 *
	 * @return 	KTemplateStack
	 */
	public function getStack()
	{
	    return $this->_stack;
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
	 *                  implements KIdentifierInterface or valid identifier string
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
	 * @param	boolean	If TRUE process the data using a tmpl stream. Default TRUE.
	 * @return KTemplateAbstract
	 */
	public function loadIdentifier($template, $data = array(), $process = true)
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
	 
	    // Find the template 
		$file = $this->findFile($path.'/'.$file.'.php');
	    
		if ($file === false) {
			throw new KTemplateException( 'Template "' . $file . '" not found' );
		}
		
		// Load the file
		$this->loadFile($file, $data, $process);
		
		return $this;
	}
	
	/**
	 * Load a template by path
	 *
	 * @param   string 	The template path
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @param	boolean	If TRUE process the data using a tmpl stream. Default TRUE.
	 * @return KTemplateAbstract
	 */
	public function loadFile($file, $data = array(), $process = true)
	{
		// store the path
		$this->_path  = $file;
		
		// get the file contents
		$contents = file_get_contents($file);
		
		// load the contents
		$this->loadString($contents, $data, $process);
		
		return $this;
	}
	
	/**
	 * Load a template from a string
	 *
	 * @param   string 	The template contents
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @param	boolean	If TRUE process the data using a tmpl stream. Default TRUE.
	 * @return KTemplateAbstract
	 */
	public function loadString($string, $data = array(), $process = true)
	{
		$this->_contents = $string;
	
		// Merge the data
	    $this->_data = array_merge((array) $this->_data, $data);
	    
	    // Process the data
	    if($process == true) {
	        $this->__sandbox();
	    }
	
		return $this;
	}
	
	/**
	 * Render the template
	 * 
	 * This function passes the template throught write filter chain and returns the
	 * result.
	 *
	 * @return string	The rendered data
	 */
	public function render()
	{	
		$context = $this->getCommandContext();
		$context->data = $this->_contents;
				
        $result = $this->getCommandChain()->run(KTemplateFilter::MODE_WRITE, $context);
        
        return $context->data;
	}
	
	/**
	 * Parse the template
	 * 
	 * This function passes the template throught read filter chain and returns the
	 * result.
	 *
	 * @return string	The parsed data
	 */
	public function parse()
	{	
        $context = $this->getCommandContext();
		$context->data = $this->_contents;
				
        $result = $this->getCommandChain()->run(KTemplateFilter::MODE_READ, $context);
        
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
 		$filters =  (array) KConfig::toData($filters);
 	    
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
		
		$helper = $this->getHelper(implode('.', $parts));
		
		//Call the helper function
		if (!is_callable( array( $helper, $function ) )) {
			throw new KTemplateHelperException( get_class($helper).'::'.$function.' not supported.' );
		}	
		
		return $helper->$function($params);
	}
	
	/**
	 * Get a template helper
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIdentifierInterface or valid identifier string
	 * @param	mixed	Parameters to be passed to the helper
	 * @return 	KTemplateHelperInterface
	 */
	public function getHelper($helper)
	{	
		//Create the complete identifier if a partial identifier was passed
		if(is_string($helper) && strpos($helper, '.') === false ) 
		{
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('template','helper');
            $identifier->name = $helper;
		}
		else $identifier = KFactory::identify($helper);
	 
		//Create the template helper
		$helper = KTemplateHelper::factory($identifier, array('template' => $this));
		
		return $helper;
	}
	
	/**
	 * Process the template using a simple sandbox
	 * 
	 * This function passes the template through the read filter chain before letting
	 * the PHP parser executed it. The result is buffered.
	 *
	 * @param  boolean 	If TRUE apply write filters. Default FALSE.
	 * @return KTemplateAbstract
	 */
	private function __sandbox()
	{	
	    //Set the error handler
        set_error_handler(array($this, 'sandboxError'), E_WARNING | E_NOTICE);
	    
	    //Set the template in the template stack
       	$this->getStack()->push(clone $this);
       
       	extract($this->_data, EXTR_SKIP); //extract the data in local scope
       	
       	// Capturing output into a buffer
		ob_start();
		include 'tmpl://'.$this->getStack()->getIdentifier();
		$this->_contents = ob_get_clean();
		
		//Remove the template object from the template stack
       	$this->getStack()->pop();
       	
       	//Restore the error handler
        restore_error_handler();
		
		return $this;
	}
	
 	/**
     * Hanlde sandbox errors
     * 
     * @return bool
     */
    public function sandboxError($code, $message, $file = '', $line = 0, $context = array())
    {
        if($file == 'tmpl://lib.koowa.template.stack') 
        {
            if(ini_get('display_errors')) {
                echo '<strong>'.self::$_errors[$code].'</strong>: '.$message.' in <strong>'.$this->_path.'</strong> on line <strong>'.$line.'</strong>';
            }
            
            if(ini_get('log_errors')) {
                error_log(sprintf('PHP %s:  %s in %s on line %d', self::$_errors[$code], $message, $this->_path, $line));
            }
            
            return true;
        }
        
        return false;
    }

	/**
	 * Renders the template and returns the result
 	 *
	 * @return 	string
	 */
	public function __toString()
	{
		try {
		    $result = $this->_contents;
		} catch (Exception $e) {
			$result = $e->getMessage();
		} 
			
		return $result;
	}
}