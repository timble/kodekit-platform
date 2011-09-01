<?php
/**
* @version		$Id: application.php 16449 2010-04-25 21:17:31Z ian $
* @package		Joomla.Framework
* @subpackage	Application
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
* Base class for a Joomla! application.
*
* Acts as a Factory class for application specific objects and provides many
* supporting API functions. Derived clases should supply the route(), dispatch()
* and render() functions.
*
* @abstract
* @package		Joomla.Framework
* @subpackage	Application
* @since		1.5
*/

class JApplication extends JObject
{
	/**
	 * The client identifier.
	 *
	 * @var		integer
	 * @access	protected
	 * @since	1.5
	 */
	var $_clientId = null;

	/**
	 * The application message queue.
	 *
	 * @var		array
	 * @access	protected
	 */
	var $_messageQueue = array();

	/**
	 * The name of the application
	 *
	 * @var		array
	 * @access	protected
	 */
	var $_name = null;

	/**
	 * The scope of the application
	 *
	 * @var		string
	 * @access	public
	 */
	var $scope = null;

	/**
	 * The site id we are using.
	 *
	 * @var string
	 */
	var $_site = '';

	/**
	* Class constructor.
	*
	* @param	integer	A client identifier.
	*/
    function __construct($config = array())
	{
		jimport('joomla.utilities.utility');

		//set the view name
		$this->_name		= $this->getName();
		$this->_clientId	= $config['clientId'];

		//Enable sessions by default
		if(!isset($config['session'])) {
			$config['session'] = true;
		}

		//Set the session default name
		if(!isset($config['session_name'])) {
			 $config['session_name'] = $this->_name;
		}

		//Set the default configuration file
		if(!isset($config['config_file'])) {
			$config['config_file'] = 'configuration.php';
		}

		//Setup the request
        KRequest::root(str_replace('/'.$this->getName(), '', KRequest::base()));

		//create the configuration object
		$this->_loadConfiguration(JPATH_CONFIGURATION.DS.$config['config_file']);

		//set defines
		define('JPATH_CACHE', $this->getCfg('cache_path', JPATH_ROOT.'/cache'));

		//Set the session autostart
		if(!isset($config['session_autostart'])) {
			 $config['session_autostart'] = !is_null($this->getCfg('session_autostart')) ? $this->getCfg('session_autostart') :  true;
		}

		//create the session if a session name is passed
		if($config['session'] !== false) {
			$this->_loadSession(JUtility::getHash($config['session_name']), false, $config['session_autostart']);
		}

		//create the site
		if(isset($config['multisite']) && $config['multisite'] == true)
		{
	        //Set the session default name
		    if(!isset($config['site'])) {
			    $config['site'] = 'default';
		    }

		    $this->_loadSite($config['site']);
		}

		$this->set( 'requestTime', gmdate('Y-m-d H:i') );
	}

	/**
	 * Returns a reference to the global JApplication object, only creating it if it
	 * doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		<pre>  $menu = &JApplication::getInstance();</pre>
	 *
	 * @access	public
	 * @param	mixed	$id 		A client identifier or name.
	 * @param	array	$config 	An optional associative array of configuration settings.
	 * @return	JApplication	The appliction object.
	 * @since	1.5
	 */
	function &getInstance($client, $config = array(), $prefix = 'J')
	{
		static $instances;

		if (!isset( $instances )) {
			$instances = array();
		}

		if (empty($instances[$client]))
		{
			//Load the router object
			jimport('joomla.application.helper');
			$info =& JApplicationHelper::getClientInfo($client, true);

			$path = $info->path.DS.'includes'.DS.'application.php';
			if(file_exists($path))
			{
				require_once $path;

				// Create a JRouter object
				$classname = $prefix.ucfirst($client);
				$instance = new $classname($config);
			}
			else
			{
				$error = JError::raiseError(500, 'Unable to load application: '.$client);
				return $error;
			}

			$instances[$client] =& $instance;
		}

		return $instances[$client];
	}

   /**
	* Initialise the application.
	*
	* @param	array An optional associative array of configuration settings.
	* @access	public
	*/
	function initialise($options = array())
	{
		// Check that we were given a language in the array (since by default may be blank)
		if(isset($options['language'])) {
			JFactory::getConfig()->setValue('config.language', $options['language']);
		}

		// Set user specific editor
		JFactory::getConfig()->setValue('config.editor', $this->getCfg('editor'));
	}

	/**
	* Route the application.
	*
	* Routing is the process of examining the request environment to determine which
	* component should receive the request. The component optional parameters
	* are then set in the request object to be processed when the application is being
	* dispatched.
	*
	* @abstract
	* @param	object A JURI object.
	* @access	public
	*/
	function route($uri = null)
 	{
		if(!isset($uri)) {
		    $uri = clone(JURI::getInstance());
		}

	    $result = $this->getRouter()->parse($uri);
		JRequest::set($result, 'get', false );
 	}

 	/**
	* Dispatch the applicaiton.
	*
	* Dispatching is the process of pulling the option from the request object and
	* mapping them to a component. If the component does not exist, it handles
	* determining a default component to dispatch.
	*
	* @abstract
	* @access	public
	*/
 	function dispatch($component)
 	{
		$document =& JFactory::getDocument();

		$document->setTitle( $this->getCfg('sitename' ). ' - ' .JText::_( 'Administration' ));

		$contents = JComponentHelper::renderComponent($component);
		$document->setBuffer($contents, 'component');
 	}

	/**
	* Render the application.
	*
	* Rendering is the process of pushing the document buffers into the template
	* placeholders, retrieving data from the document and pushing it into
	* the JResponse buffer.
	*
	* @abstract
	* @access	public
	*/
	function render()
	{
		$params = array(
			'template' 	=> $this->getTemplate(),
			'file'		=> 'index.php',
			'directory'	=> JPATH_THEMES
		);

		$document =& JFactory::getDocument();
		$data = $document->render($this->getCfg('caching'), $params );
		JResponse::setBody($data);
	}

	/**
	* Exit the application.
	*
	* @access	public
	* @param	int	Exit code
	*/
	function close( $code = 0 ) {
		exit($code);
	}

	/**
	 * Redirect to another URL.
	 *
	 * Optionally enqueues a message in the system message queue (which will be displayed
	 * the next time a page is loaded) using the enqueueMessage method. If the headers have
	 * not been sent the redirect will be accomplished using a "301 Moved Permanently" or "303 See Other"
	 * code in the header pointing to the new location depending upon the moved flag. If the headers
	 * have already been sent this will be accomplished using a JavaScript statement.
	 *
	 * @access	public
	 * @param	string	$url	The URL to redirect to. Can only be http/https URL
	 * @param	string	$msg	An optional message to display on redirect.
	 * @param	string  $msgType An optional message type.
	 * @param	boolean	True if the page is 301 Permanently Moved, otherwise 303 See Other is assumed.
	 * @return	none; calls exit().
	 * @since	1.5
	 * @see		JApplication::enqueueMessage()
	 */
	function redirect( $url, $msg='', $msgType='message', $moved = false )
	{
		// check for relative internal links
		if (preg_match( '#^index[2]?.php#', $url )) {
			$url = JURI::base() . $url;
		}

		// Strip out any line breaks
		$url = preg_split("/[\r\n]/", $url);
		$url = $url[0];

		// If we don't start with a http we need to fix this before we proceed
		// We could validly start with something else (e.g. ftp), though this would
		// be unlikely and isn't supported by this API
		if(!preg_match( '#^http#i', $url )) {
			$uri =& JURI::getInstance();
			$prefix = $uri->toString(Array('scheme', 'user', 'pass', 'host', 'port'));
			if($url[0] == '/') {
				// we just need the prefix since we have a path relative to the root
				$url = $prefix . $url;
			} else {
				// its relative to where we are now, so lets add that
				$parts = explode('/', $uri->toString(Array('path')));
				array_pop($parts);
				$path = implode('/',$parts).'/';
				$url = $prefix . $path . $url;
			}
		}


		// If the message exists, enqueue it
		if (trim( $msg )) {
			$this->enqueueMessage($msg, $msgType);
		}

		// Persist messages if they exist
		if (count($this->_messageQueue))
		{
			$session =& JFactory::getSession();
			$session->set('application.queue', $this->_messageQueue);
		}

		// If the headers have been sent, then we cannot send an additional location header
		// so we will output a javascript redirect statement.
		if (headers_sent()) {
			echo "<script>document.location.href='$url';</script>\n";
		} else {
			header($moved ? 'HTTP/1.1 301 Moved Permanently' : 'HTTP/1.1 303 See other');
			header('Location: '.$url);
		}
		$this->close();
	}

	/**
	 * Enqueue a system message.
	 *
	 * @access	public
	 * @param	string 	$msg 	The message to enqueue.
	 * @param	string	$type	The message type.
	 * @return	void
	 * @since	1.5
	 */
	function enqueueMessage( $msg, $type = 'message' )
	{
		// For empty queue, if messages exists in the session, enqueue them first
		if (!count($this->_messageQueue))
		{
			$session =& JFactory::getSession();
			$sessionQueue = $session->get('application.queue');
			if (count($sessionQueue)) {
				$this->_messageQueue = $sessionQueue;
				$session->set('application.queue', null);
			}
		}
		// Enqueue the message
		$this->_messageQueue[] = array('message' => $msg, 'type' => strtolower($type));
	}

	/**
	 * Get the system message queue.
	 *
	 * @access	public
	 * @return	The system message queue.
	 * @since	1.5
	 */
	function getMessageQueue()
	{
		// For empty queue, if messages exists in the session, enqueue them
		if (!count($this->_messageQueue))
		{
			$session =& JFactory::getSession();
			$sessionQueue = $session->get('application.queue');
			if (count($sessionQueue)) {
				$this->_messageQueue = $sessionQueue;
				$session->set('application.queue', null);
			}
		}
		return $this->_messageQueue;
	}

	 /**
	 * Gets a configuration value.
	 *
	 * @access	public
	 * @param	string	The name of the value to get.
	 * @return	mixed	The user state.
	 * @example	application/japplication-getcfg.php Getting a configuration value
	 */
	function getCfg( $varname, $default = null )
	{
		$config =& JFactory::getConfig();
		return $config->getValue('config.' . $varname, $default);
	}

	/**
	 * Method to get the application name
	 *
	 * The dispatcher name by default parsed using the classname, or it can be set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @access	public
	 * @return	string The name of the dispatcher
	 * @since	1.5
	 */
	function getName()
	{
		$name = $this->_name;

		if (empty( $name ))
		{
			$r = null;
			if ( !preg_match( '/J(.*)/i', get_class( $this ), $r ) ) {
				JError::raiseError(500, "JApplication::getName() : Can\'t get or parse class name.");
			}
			$name = strtolower( $r[1] );
		}

		return $name;
	}

	/**
	 * Gets a user state.
	 *
	 * @access	public
	 * @param	string	The path of the state.
	 * @param	mixed	Optional default value
	 * @return	mixed	The user state.
	 */
	function getUserState( $key, $default = null )
	{
		$session	=& JFactory::getSession();
		$registry	=& $session->get('registry');
		if(!is_null($registry)) {
			return $registry->getValue($key, $default);
		}
		return null;
	}

	/**
	* Sets the value of a user state variable.
	*
	* @access	public
	* @param	string	The path of the state.
	* @param	string	The value of the variable.
	* @return	mixed	The previous state, if one existed.
	*/
	function setUserState( $key, $value )
	{
		$session	=& JFactory::getSession();
		$registry	=& $session->get('registry');
		if(!is_null($registry)) {
			return $registry->setValue($key, $value);
		}
		return null;
	}

	/**
	 * Gets the value of a user state variable.
	 *
	 * @access	public
	 * @param	string	The key of the user state variable.
	 * @param	string	The name of the variable passed in a request.
	 * @param	string	The default value for the variable if not found. Optional.
	 * @param	string	Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
	 * @return	The request user state.
	 */
	function getUserStateFromRequest( $key, $request, $default = null, $type = 'none' )
	{
		$old_state = $this->getUserState( $key );
		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = JRequest::getVar($request, null, 'default', $type);

		// Save the new value only if it was set in this request
		if ($new_state !== null) {
			$this->setUserState($key, $new_state);
		} else {
			$new_state = $cur_state;
		}

		return $new_state;
	}

	/**
	 * Registers a handler to a particular event group.
	 *
	 * @static
	 * @param	string	The event name.
	 * @param	mixed	The handler, a function or an instance of a event object.
	 * @return	void
	 * @since	1.5
	 */
	function registerEvent($event, $handler)
	{
		$dispatcher =& JDispatcher::getInstance();
		$dispatcher->register($event, $handler);
	}

	/**
	 * Calls all handlers associated with an event group.
	 *
	 * @static
	 * @param	string	The event name.
	 * @param	array	An array of arguments.
	 * @return	array	An array of results from each function call.
	 * @since	1.5
	 */
	function triggerEvent($event, $args=null)
	{
		$dispatcher =& JDispatcher::getInstance();
		return $dispatcher->trigger($event, $args);
	}

	/**
	 * Login authentication function.
	 *
	 * Username and encoded password are passed the the onLoginUser event which
	 * is responsible for the user validation. A successful validation updates
	 * the current session record with the users details.
	 *
	 * Username and encoded password are sent as credentials (along with other
	 * possibilities) to each observer (authentication plugin) for user
	 * validation.  Successful validation will update the current session with
	 * the user details.
	 *
	 * @param	array 	Array( 'username' => string, 'password' => string )
	 * @return	boolean True on success.
	 * @access	public
	 * @since	1.5
	 */
	function login($credentials, $options = array())
	{
		//Force the site
		$options['site'] = $this->_site;

	    // Get the global JAuthentication object
		jimport( 'joomla.user.authentication');
		$authenticate = & JAuthentication::getInstance();
		$response	  = $authenticate->authenticate($credentials, $options);

		if ($response->status === JAUTHENTICATE_STATUS_SUCCESS)
		{
			$session = &JFactory::getSession();

			// we fork the session to prevent session fixation issues
			$session->fork();
			$this->_loadSession($session->getId());

			// Import the user plugin group
			JPluginHelper::importPlugin('user');

			// OK, the credentials are authenticated.  Lets fire the onLogin event
			$results = $this->triggerEvent('onLoginUser', array((array)$response, $options));

			/*
			 * If any of the user plugins did not successfully complete the login routine
			 * then the whole method fails.
			 *
			 * Any errors raised should be done in the plugin as this provides the ability
			 * to provide much more information about why the routine may have failed.
			 */

			if (!in_array(false, $results, true)) {
				return true;
			}
		}

		// Trigger onLoginFailure Event
		$this->triggerEvent('onLoginFailure', array((array)$response));


		// If silent is set, just return false
		if (isset($options['silent']) && $options['silent']) {
			return false;
		}

		// Return the error
		return JError::raiseWarning('SOME_ERROR_CODE', JText::_('E_LOGIN_AUTHENTICATE'));
	}

	/**
	 * Logout authentication function.
	 *
	 * Passed the current user information to the onLogoutUser event and reverts the current
	 * session record back to 'anonymous' parameters.
	 *
	  * @param 	int 	$userid   The user to load - Can be an integer or string - If string, it is converted to ID automatically
	 * @param	array 	$options  Array( 'clientid' => array of client id's )
	 *
	 * @access public
	 */
	function logout($userid = null, $options = array())
	{
		// Initialize variables
		$retval = false;

		// Get a user object from the JApplication
		$user = &JFactory::getUser($userid);

		// Build the credentials array
		$parameters['username']	= $user->get('username');
		$parameters['id']		= $user->get('id');

		// Set clientid in the options array if it hasn't been set already
		if(empty($options['clientid'])) {
			$options['clientid'][] = $this->getClientId();
		}

		// Import the user plugin group
		JPluginHelper::importPlugin('user');

		// OK, the credentials are built. Lets fire the onLogout event
		$results = $this->triggerEvent('onLogoutUser', array($parameters, $options));

		/*
		 * If any of the authentication plugins did not successfully complete
		 * the logout routine then the whole method fails.  Any errors raised
		 * should be done in the plugin as this provides the ability to provide
		 * much more information about why the routine may have failed.
		 */
		if (!in_array(false, $results, true)) {
			return true;
		}

		// Trigger onLoginFailure Event
		$this->triggerEvent('onLogoutFailure', array($parameters));

		return false;
	}

	/**
	 * Gets the name of the current template.
	 *
	 * @return	string
	 */
	function getTemplate()
	{
		return 'system';
	}

	/**
	 * Gets the name of site
	 *
	 * @return	string
	 */
	public function getSite()
	{
		return $this->_site;
	}

	/**
	 * Return a reference to the application JRouter object.
	 *
	 * @access	public
	 * @param  array	$options 	An optional associative array of configuration settings.
	 * @return	JRouter.
	 * @since	1.5
	 */
	function &getRouter($name = null, $options = array())
	{
		if(!isset($name)) {
			$name = $this->_name;
		}

		jimport( 'joomla.application.router' );
		$router =& JRouter::getInstance($name, $options);
		if (JError::isError($router)) {
			$null = null;
			return $null;
		}
		return $router;
	}

	/**
	 * Return a reference to the application JPathway object.
	 *
	 * @access public
	 * @param  array	$options 	An optional associative array of configuration settings.
	 * @return object JPathway.
	 * @since 1.5
	 */
	function &getPathway($name = null, $options = array())
	{
		if(!isset($name)) {
			$name = $this->_name;
		}

		jimport( 'joomla.application.pathway' );
		$pathway =& JPathway::getInstance($name, $options);
		if (JError::isError($pathway)) {
			$null = null;
			return $null;
		}
		return $pathway;
	}

	/**
	 * Return a reference to the application JPathway object.
	 *
	 * @access public
	 * @param  array	$options 	An optional associative array of configuration settings.
	 * @return object JMenu.
	 * @since 1.5
	 */
	function &getMenu($name = null, $options = array())
	{
		if(!isset($name)) {
			$name = $this->_name;
		}

		jimport( 'joomla.application.menu' );
		$menu =& JMenu::getInstance($name, $options);
		if (JError::isError($menu)) {
			$null = null;
			return $null;
		}
		return $menu;
	}

	/**
	 * Load the site
	 *
	 * @param	string	$site 	The name of the site to load
	 * @return	void
	 * @throws  KException 	If the site could not be found
	 * @since	Nooku Server 0.7
	 */
    protected function _loadSite($site)
	{
	    // Check if the site exists
	    if(! KFactory::get('com://admin/sites.model.sites')->getList()->find($site))
	    {
            throw new KException('Site :'.$site.' not found', KHttpResponse::NOT_FOUND);
            return false;
        }

		//Load the site configuration
		require_once( JPATH_SITES.'/'.$site.'/settings.php');
		JFactory::getConfig()->loadObject(new JSettings());

		//Set the site error reporting
		$error_reporting = $this->getCfg('error_reporting');
		if ($error_reporting >= 0) {
			error_reporting( $error_reporting );
		}

		//Set the site debug mode
		define( 'JDEBUG', $this->getCfg('debug') );

		//Force re-creation of the database connection
		$db =& JFactory::getDBO();
		$db = null;

	    //Set the paths
		$params = JComponentHelper::getParams('com_files');

		define('JPATH_FILES'    , JPATH_SITES.'/'.$site);
	    define('JPATH_IMAGES'   , JPATH_SITES.'/'.$site.'/'.$params->get('image_path', 'images'));

		//Force re-login of the user if the site changed
		$user = KFactory::get('joomla:user');

		if(!$user->get('guest') && (JFactory::getSession()->get('site') != $site))
		{
		    $session = KFactory::get('joomla:session');

		    // Fork the session to prevent session fixation issues if it's active
			$session->fork();
			if($session->getState() != 'active') {
				$session->start();
			} else {
				$session->fork();
			}

			$this->_loadSession($session->getId());

			JPluginHelper::importPlugin('user');
			JPluginHelper::importPlugin('system');

			$response = array(
				'username' 		 => $user->get('username'),
				'email'	   		 => $user->get('email'),
				'fullname' 		 => $user->get('fullname'),
				'password_clear' => ''
			);

			$options = array(
				'group' 		=> $this->isAdmin() ? 'Public Backend' : 'USERS',
				'autoregister' 	=> false,
			    'site'			=> $site
			);

			$results = $this->triggerEvent('onLoginUser', array($response, $options));

			if(JError::isError($results[0]))
			{
			    $this->triggerEvent('onLoginFailure', array((array)$response));

				//Log the user out
				$this->logout();
			}
		}

		//Set the site in the application
		$this->_site = $site;
	}

	/**
	 * Load the application configuration
	 *
	 * @param	string	$file 	The path to the configuration file
	 * @return	JConfig
	 */
	protected function _loadConfiguration($file)
	{
		jimport( 'joomla.registry.registry' );

		require_once( $file );

		// Create the JConfig object
		$config = new JConfig();

		// Get the global configuration object
		$registry =& JFactory::getConfig();

		// Load the configuration values into the registry
		$registry->loadObject($config);

		return $config;
	}

	/**
	 * Load the user session or create a new one
	 *
	 * Old sessions are flushed based on the configuration value for the cookie
	 * lifetime. If an existing session, then the last access time is updated.
	 * If a new session, a session id is generated and a record is created in
	 * the #__sessions table.
	 *
	 * @param	string	The sessions name.
	 * @return	object	JSession on success. May call exit() on database error.
	 * @since	1.5
	 */
	protected function _loadSession( $name, $ssl = false, $auto_start = true )
	{
		$options = array(
			'name' 	 	 => $name,
			'force_ssl'  => $ssl
		);

		//Create the session object
		$session = JFactory::getSession($options);

		//Auto-start the session if a cookie is found or if auto_start is true
		if($session->getState() != 'active')
		{
			if ($auto_start || JRequest::getCmd($session->getName(), null, 'cookie')) {
				$session->start();
			}
		}

		//Only update the session table if the session is active
		if($session->getState() == 'active')
		{
			jimport('joomla.database.table');
			$storage = & JTable::getInstance('session');
			$storage->purge($session->getExpire());

			// Session exists and is not expired, update time in session table
			if ($storage->load($session->getId())) {
				$storage->update();
			}
			else
			{
				//Session doesn't exist, initalise and store it in the session table
				$session->set('registry',	new JRegistry('session'));
				$session->set('user',		new JUser());

				if (!$storage->insert( $session->getId(), $this->getClientId())) {
					jexit( $storage->getError());
				}
			}
		}
		else
		{
			$session->set('registry',	new JRegistry('session'));
			$session->set('user',		new JUser());
		}

		return $session;
	}

	/**
	 * Gets the client id of the current running application.
	 *
	 * @access	public
	 * @return	int A client identifier.
	 * @since	1.5
	 */
	function getClientId( )
	{
		return $this->_clientId;
	}

	/**
	 * Is admin interface?
	 *
	 * @access	public
	 * @return	boolean		True if this application is administrator.
	 * @since	1.0.2
	 */
	function isAdmin()
	{
		return ($this->_clientId == 1);
	}

	/**
	 * Is site interface?
	 *
	 * @access	public
	 * @return	boolean		True if this application is site.
	 * @since	1.5
	 */
	function isSite()
	{
		return ($this->_clientId == 0);
	}
}