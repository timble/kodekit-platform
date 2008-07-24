<?php
/**
 * @version		$Id$
 * @package		Koowa_Controller
 * @copyright	Copyright (C) 2007 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract Controller Class
 *
 * Note: Concrete controllers must have a singular name
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package		Koowa_Controller
 * @uses		KPatternClass
 */
abstract class KControllerAbstract extends KObject
{
	/**
	 * The base path
	 *
	 * @var		string
	 */
	protected $_basePath;

	/**
	 * Array of class methods
	 *
	 * @var	array
	 */
	protected $_methods = array();

	/**
	 * Array of class methods to call for a given task.
	 *
	 * @var	array
	 */
	protected $_taskMap = array();

	/**
	 * Current or most recent task to be performed.
	 *
	 * @var	string
	 */
	protected $_task 	= null;

	/**
	 * The mapped task that was performed.
	 *
	 * @var	string
	 */
	protected $_doTask 	= null;

	/**
	 * The set of search directories for resources (views).
	 *
	 * @var array
	 */
	protected $_path = array(
		'view'	=> array(),
	);

	/**
	 * URL for redirection.
	 *
	 * @var	string
	 */
	protected $_redirect = null;

	/**
	 * Redirect message.
	 *
	 * @var	string
	 */
	protected $_message = null;

	/**
	 * Redirect message type.
	 *
	 * @var	string
	 */
	protected $_messageType = 'message';
	
	/**
	 * The commandchain
	 *
	 * @var	object
	 */
	protected $_commandChain = null;

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'view_path'
	 * (this list is not meant to be comprehensive).
	 */
	public function __construct( $options = array() )
	{
        // Initialize the options
        $options  = $this->_initialize($options);
        
        //Create the command chain
        $this->_commandChain = $options['command_chain'];
        $this->_commandChain->enqueue(new KCommandEvent());

        // Mixin the KPatternClass and KPatternCommandChain
        $this->mixin(new KPatternClass($this, 'Controller'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Get the methods only for the final controller class
		$thisMethods	= get_class_methods( get_class( $this ) );
		$baseMethods	= get_class_methods( 'KControllerAbstract' );
		$methods		= array_diff( $thisMethods, $baseMethods );

		// Add default display method
		$methods[] = 'display';

		// Iterate through methods and map tasks
		foreach ( $methods as $method )
		{
			if ( substr( $method, 0, 1 ) != '_' )
            {
				$this->_methods[] = strtolower( $method );
				// auto register public methods as tasks
				$this->_taskMap[strtolower( $method )] = $method;
			}
		}

		// Set a base path for use by the controller
		$this->_basePath	= $options['base_path'];

		// If the default task is set, register it as such
		$this->registerDefaultTask( $options['default_task'] );

        // set the default view search path
		$this->_setPath( 'view', $options['view_path'] );
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
            'base_path'     => null,
            'default_task'  => 'display',
            'name'          => array(
                        'prefix'    => 'k',
                        'base'      => 'controller',
                        'suffix'    => 'default'
                        ),
            'view_path'     => null,
            'command_chain' =>  new KPatternCommandChain()           
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Execute a task by triggering a method in the derived class.
	 *
	 * @param	string The task to perform. If no matching task is found, the
	 * 				 	'__default' task is executed, if defined.
	 * @return	mixed|false The value returned by the called method, false in error case.
	 */
	public function execute( $task )
	{
		$this->_task = $task;

		//Convert to lower case for lookup
		$task = strtolower( $task );
		
		$doTask = $this->_taskMap['__default'];
		if (isset( $this->_taskMap[$task] )) {
			$doTask = $this->_taskMap[$task];
		} 

		// Record the actual task being fired
		$this->_doTask = $doTask;
		
		//Create the arguments object
		$args = new stdClass();
		$args->class_name = $this->getClassName();
		$args->task  = $task;
		
		if($this->_commandChain->execute('onBeforeController'.ucfirst($doTask), $args) === true) {
			$args->result = $this->$doTask();
			$this->_commandChain->execute('onAfterController'.ucfirst($doTask), $args);
		}
		
		return $args->result;
	}

	/**
	 * Typical view method for MVC based architecture
	 *
	 * This function is provide as a default implementation, in most cases
	 * you will need to override it in your own controllers.
	 *
	 * @param	string	$cachable	If true, the view output will be cached
	 */
	public function display($cachable = false)
	{
		$viewName	= JRequest::getCmd( 'view', $this->getClassName('suffix') );
		$viewLayout	= JRequest::getCmd( 'layout', 'default' );

		$view       = $this->getView($viewName);

		// Set the layout
		$view->setLayout($viewLayout);

		// Display the view
		if ($cachable)
		{
			global $option;
			$cache = KFactory::get('Cache', $option, 'view');
			$cache->get($view, 'display');
		}
		else
        {
            $view->display();
        }
	}

	/**
	 * Redirects the browser or returns false if no redirect is set.
	 *
	 * @return	boolean	False if no redirect exists.
	 */
	public function redirect()
	{
		if ($this->_redirect)
		{
			$app = KFactory::get('Application');
			$app->redirect( $this->_redirect, $this->_message, $this->_messageType );
		}

		return false;
	}

	/**
	 * Gets the available tasks in the controller.
	 *
	 * @return	array Array[i] of task names.
	 */
	public function getTasks()
	{
		return $this->_methods;
	}

	/**
	 * Get the last task that is or was to be performed.
	 *
	 * @return	 string The task that was or is being performed.
	 */
	public function getTask()
	{
		return $this->_task;
	}

	/**
	 * Method to get a reference to the current view and load it if necessary.
	 *
	 * @param	string	The view name. Optional, defaults to the controller name.
	 * @param	string	The class prefix. Optional.
	 * @param	array	Options array for view. Optional.
	 * @return	object	Reference to the view or an error.
	 */
	public function getView( $name = '', $prefix = '', $options = array() )
	{
		if ( empty( $prefix ) ) {
			$prefix = $this->getClassName('prefix');
		}

		if ( empty( $name ) ) {
			$name = $this->getClassName('suffix');
		}

		//Add the basepath to the configuration
		$options['base_path'] = $this->_path['view'][0];
		$object = array(
			'type' 		=> 'view'  ,
			'component'	=> $prefix ,
			'name'		=> $name
		);

		if ( !$view = KFactory::getInstance($object, $options) )
		{
            $format = isset($options['format']) ? $options['format'] : 'html';
			$result = JError::raiseError( 500,
                        JText::_('View not found [name, format, prefix]:')
                        ." $name, $format, $prefix"
				);
			return $result;
		}

		return $view;
	}

	/**
	 * Add one or more view paths to the controller's stack, in LIFO order.
	 *
	 * @param	string|array The directory (string), or list of directories (array) to add.
	 * @return	void
	 */
	public function addViewPath( $path )
	{
		$this->_addPath( 'view', $path );
	}

	/**
	 * Register (map) a task to a method in the class.
	 *
	 * @param	string	The task.
	 * @param	string	The name of the method in the derived class to perform
	 *                  for this task.
	 * @return	void
	 */
	public function registerTask( $task, $method )
	{
		if ( in_array( strtolower( $method ), $this->_methods ) ) {
			$this->_taskMap[strtolower( $task )] = $method;
		}
	}

	/**
	 * Register the default task to perform if a mapping is not found.
	 *
	 * @param	string The name of the method in the derived class to perform if
	 * a named task is not found.
	 * @return	void
	 */
	public function registerDefaultTask( $method )
	{
		$this->registerTask( '__default', $method );
	}

	/**
	 * Sets the internal message that is passed with a redirect
	 *
	 * @param	string	The message
	 * @return	string	Previous message
	 */
	public function setMessage( $text )
	{
		$previous		= $this->_message;
		$this->_message = $text;
		return $previous;
	}

	/**
	 * Set a URL for browser redirection.
	 *
	 * @param	string URL to redirect to.
	 * @param	string	Message to display on redirect. Optional, defaults to
	 * 			value set internally by controller, if any.
	 * @param	string	Message type. Optional, defaults to 'message'.
	 * @return	void
	 */
	public function setRedirect( $url, $msg = null, $type = 'message' )
	{
		//Create the url if no full URL was passed
		if(strrpos($url, '?') === false) {
			$url = 'index.php?option=com_'.$this->getClassName('prefix').'&'.$url;
		}

		$url = JRoute::_($url, false);
		$this->_redirect = $url;

		// controller may have set this directly
		if ($msg !== null) {
			$this->_message	= $msg;
		}

		$this->_messageType	= $type;
	}

	/**
	* Sets an entire array of search paths for resources.
	*
	* @param	string	The type of path to set, typically 'view' or 'model'.
	* @param	string|array	The new set of search paths. If null or false,
	* resets to the current directory only.
	*/
	protected function _setPath( $type, $path )
	{
		// clear out the prior search dirs
		$this->_path[$type] = array();

		// actually add the user-specified directories
		$this->_addPath( $type, $path );
	}

	/**
	* Adds to the search path for templates and resources.
	*
	* @param	string The path type (e.g. 'model', 'view'.
	* @param	string|array The directory or stream to search.
	* @return	void
	*/
	protected function _addPath( $type, $path )
	{
		// just force path to array
		settype( $path, 'array' );

		// loop through the path directories
		foreach ( $path as $dir )
		{
			// no surrounding spaces allowed!
			$dir = trim( $dir );

			// add trailing separators as needed
			if ( substr( $dir, -1 ) != DIRECTORY_SEPARATOR ) {
				// directory
				$dir .= DIRECTORY_SEPARATOR;
			}

			// add to the top of the search dirs
			array_unshift( $this->_path[$type], $dir );
		}
	}
}
