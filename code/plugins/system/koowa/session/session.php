<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Session
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Class for managing HTTP sessions
 *
 * Provides access to session-state values as well as session-level
 * settings and lifetime management methods.
 * Based on the standart PHP session handling mechanism it provides
 * for you more advanced features such as expire timeouts.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Session
 * @uses		KFactory
 */
class KSession extends KObject
{
	/**
	 * internal state
	 *
	 * @var	string one of 'active'|'expired'|'destroyed|'error'
	 * @see getState()
	 */
	protected $_state	= 'active';

	/**
	 * Maximum age of unused session
	 *
	 * @var	integer The session expiration time in minutes
	 * @see getExpire()
	 */
	protected $_expire = 15;

	/**
	 * The session handler
	 *
	 * @var	KSessionHandler
	 */
	protected $_handler =	null;

	/**
	 * The seecurity policy
	 *
	 * Default values:
	 *  - fix_browser
	 *
	 * @var array list of checks that will be done.
	 */
	protected $_security = array();

	/**
	 * Constructor
	 *
	 * @param string|object The name of the session handler or a session handler object 
	 * @param array 		Optional parameters
	 */
	public function __construct( array $options = array() )
	{
		//Initialize the options
        $options  = $this->_initialize($options);
		
		//Need to destroy any existing sessions started with session.auto_start
		if (session_id()) 
		{
			session_unset();
			session_destroy();
		}

		//Set default sessios save handler
		ini_set('session.save_handler', 'files');

		//Disable transparent sid support
		ini_set('session.use_trans_sid', '0');
		
		//Sync the session maxlifetime
		ini_set('session.gc_maxlifetime', $options['expire']);
      
		//Set the session name
		session_name( md5($options['name']) );
		
		//Set the session identifier
		session_id( $options['id'] );

		//Set expire time
		$this->_expire	= $options['expire'];
	
		//Set security options
		$this->_security = explode( ',', $options['security'] );
		
		//Create the handler
		if($options['handler'] instanceof KSessionHandlerInterface) {
			$this->_handler = $options['handler'];
		} else {
			$this->_handler = KFactory::tmp('lib.koowa.session.storage.'.$options['handler'], array($options));
		}

		//Start the session
		$this->_start();

		//Initialise the session
		$this->_setCounter();
		$this->_setTimers();

		$this->_state =	'active';

		//Perform security checks
		$this->_validate();
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
            'handler'  => 'none',
            'name'     => 'KOOWASESSID',
        	'id'	   =>  $this->_createId(),
        	'expire'   => 15,
        	'security' => array( 'fix_browser' ), 
        );

        return array_merge($defaults, $options);
    }

    /**
	 * Session object destructor
	 */
	public function __destruct() {
		$this->close();
	}

	/**
	 * Get current state of session
	 *
	 * @return string The session state
	 */
    public function getState() {
		return $this->_state;
	}

	/**
	 * Get expiration time in minutes
	 *
	 * @return integer The session expiration time in minutes
	 */
    public function getExpire() {
		return $this->_expire;
    }

	/**
	 * Get a session token, if a token isn't set yet one will be generated.
	 *
	 * Tokens are used to secure forms from spamming attacks. Once a token
	 * has been generated the system will check the post request to see if
	 * it is present, if not it will invalidate the session.
	 *
	 * @param 	boolean	If true, force a new token to be created
	 * @return 	string 	The session token
	 */
	public function getToken($forceNew = false)
	{
		$token = $this->get( 'session.token' );

		//create a token
		if( $token === null || $forceNew ) 
		{
			$token	=	$this->_createToken( 12 );
			$this->set( 'session.token', $token );
		}

		return $token;
	}

	/**
	 * Method to determine if a token exists in the session. If not the
	 * session will be set to expired
	 *
	 * @param	string	Hashed token to be verified
	 * @param	boolean	If true, expires the session
	 */
	public function hasToken($tCheck, $forceExpire = true)
	{
		// check if a token exists in the session
		$tStored = $this->get( 'session.token' );

		//check token
		if(($tStored !== $tCheck))
		{
			if($forceExpire) {
				$this->_state = 'expired';
			}
			return false;
		}

		return true;
	}


	/**
	 * Get session name
	 *
	 * @return string The session name
	 */
	public function getName()
	{
		if( $this->_state === 'destroyed' ) {
			throw new KSessionException('Session not active');
		}
		return session_name();
	}

	/**
	 * Get session id
	 *
	 * @return string The session name
	 */
	public function getId()
	{
		if( $this->_state === 'destroyed' ) {
			throw new KSessionException('Session not active');
		}
		
		return session_id();
	}

	/**
	 * Get the session handlers
	 *
	 * @return array An array of available session handlers
	 */
	public function getHandlers()
	{
		Koowa::import('lib.joomla.filesystem.folder');
		$handlers = JFolder::files(dirname(__FILE__).DS.'handler', '.php$');

		$names = array();
		foreach($handlers as $handler)
		{
			$name = substr($handler, 0, strrpos($handler, '.'));
			$class = 'KSessionHandler'.ucfirst($name);

			//Load the class only if needed
			if(class_exists($class)) 
			{
				if(call_user_func_array( array( trim($class), 'test' ), null)) {
					$names[] = $name;
				}
			}
		}

		return $names;
	}

	/**
	* Check whether this session is currently created
	*
	* @return boolean True on success
	*/
	public function isNew()
	{
		$counter = $this->get( 'session.counter' );
		if( $counter === 1 ) {
			return true;
		}
		return false;
	}

	/**
	 * Start a session
	 *
	 * Creates a session (or resumes the current one based on the state of the session)
 	 *
	 * @return boolean True on success
	 */
	protected function _start()
	{
		//  start session if not startet
		if( $this->_state == 'restart' ) {
			session_id( $this->_createId() );
		}

		session_cache_limiter('none');
		session_start();

		// Send modified header for IE 6.0 Security Policy
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');

		return true;
	}


	/**
	 * Frees all session variables and destroys all data registered to a session
	 *
	 * This method resets the $_SESSION variable and destroys all of the data associated
	 * with the current session in its storage (file or DB). It forces new session to be
	 * started after this method is called. It does not unset the session cookie.
	 *
	 * @see	session_unset()
	 * @see	session_destroy()
	 */
	public function destroy()
	{
		// session was already destroyed
		if( $this->_state === 'destroyed' ) {
			return true;
		}

		// In order to kill the session altogether, like to log the user out, the session id
		// must also be unset. If a cookie is used to propagate the session id (default behavior),
		// then the session cookie must be deleted.
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}

		session_unset();
		session_destroy();

		$this->_state = 'destroyed';
		return true;
	}

	/**
     * Restart an expired or locked session
	 *
	 * @return 	boolean True on success
	 * @see destroy
	 */
	public function restart()
	{
		$this->destroy();
		if( $this->_state !==  'destroyed' ) {
			throw new KSessionException('Session not active');
		}

		// Re-register the session handler after a session has been destroyed, to avoid PHP bug
		$this->_handler->register();

		$this->_state	=   'restart';
		
		//regenerate session id
		$id	=	$this->_createId( strlen( $this->getId() ) );
		session_id($id);
		$this->_start();
		$this->_state	=	'active';

		$this->_validate();
		$this->_setCounter();

		return true;
	}

	/**
	 * Create a new session and copy variables from the old one
	 *
	 * @return 	boolean True on success
	 */
	public function fork()
	{
		if( $this->_state !== 'active' ) {
			throw new KSessionException('Session not active');
		}

		// save values
		$values	= $_SESSION;

		// keep session config
		$trans	=	ini_get( 'session.use_trans_sid' );
		if( $trans ) {
			ini_set( 'session.use_trans_sid', 0 );
		}
		$cookie	=	session_get_cookie_params();

		// create new session id
		$id	=	$this->_createId( strlen( $this->getId() ) );

		// kill session
		session_destroy();

		// re-register the session store after a session has been destroyed, to avoid PHP bug
		$this->_handler->register();

		// restore config
		ini_set( 'session.use_trans_sid', $trans );
		session_set_cookie_params( $cookie['lifetime'], $cookie['path'], $cookie['domain'], $cookie['secure'] );

		// restart session with new id
		session_id( $id );
		session_start();

		return true;
	}

	 /**
	  * Writes session data and ends session
	  *
	  * Session data is usually stored after your script terminated without the need
	  * to call KSession::close(),but as session data is locked to prevent concurrent
	  * writes only one script may operate on a session at any time. When using
	  * framesets together with sessions you will experience the frames loading one
	  * by one due to this locking. You can reduce the time needed to load all the
	  * frames by ending the session as soon as all changes to session variables are
	  * done.
	  *
	  * @see	session_write_close()
	  */
	public function close() {
		session_write_close();
	}

	/**
	 * Create a session id
	 *
	 * @return string The session identifier
	 */
	protected function _createId( )
	{
		$id = 0;
		while (strlen($id) < 32)  {
			$id .= mt_rand(0, mt_getrandmax());
		}

		$id	= md5( uniqid($id, true));
		return $id;
	}

	/**
	 * Create a token-string
	 *
	 * @param 	integer Lenght of string
	 * @return 	string 	Generated token
	 */
	protected function _createToken( $length = 32 )
	{
		static $chars	=	'0123456789abcdef';
		$max			=	strlen( $chars ) - 1;
		$token			=	'';
		$name 			=  session_name();
		for( $i = 0; $i < $length; ++$i ) {
			$token .=	$chars[ (rand( 0, $max )) ];
		}

		return md5($token.$name);
	}

	/**
	 * Set counter of session usage
	 *
	 * @return 	boolean True on success
	 */
	protected function _setCounter()
	{
		$counter = $this->get( 'session.counter', 0 );
		++$counter;

		$this->set( 'session.counter', $counter );
		return true;
	}

	/**
	 * Set the session timers
	 *
	 * @return boolean True on success
	 */
	protected function _setTimers()
	{
		if( !$this->has( 'session.timer.start' ) )
		{
			$start	=	time();

			$this->set( 'session.timer.start' , $start );
			$this->set( 'session.timer.last'  , $start );
			$this->set( 'session.timer.now'   , $start );
		}

		$this->set( 'session.timer.last', $this->get( 'session.timer.now' ) );
		$this->set( 'session.timer.now', time() );

		return true;
	}

	/**
	 * Do some checks for security reason
	 *
	 * - timeout check (expire)
	 * - ip-fixiation
	 * - browser-fixiation
	 *
	 * If one check failed, session data has to be cleaned.
	 *
	 * @param 	boolean Reactivate session
	 * @return 	boolean True on success
	 * @see http://shiflett.org/articles/the-truth-about-sessions
	 */
	protected function _validate( $restart = false )
	{
		// allow to restart a session
		if( $restart )
		{
			$this->_state	=	'active';

			$this->set( 'session.client.address'	, null );
			$this->set( 'session.client.forwarded'	, null );
			$this->set( 'session.client.browser'	, null );
			$this->set( 'session.token'				, null );
		}

		// check if session has expired
		if( $this->_expire )
		{
			$curTime =	$this->get( 'session.timer.now' , 0  );
			$maxTime =	$this->get( 'session.timer.last', 0 ) +  $this->_expire;

			// empty session variables
			if( $maxTime < $curTime ) {
				$this->_state	=	'expired';
				return false;
			}
		}

		// record proxy forwarded for in the session in case we need it later
		if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$this->set( 'session.client.forwarded', $_SERVER['HTTP_X_FORWARDED_FOR']);
		}

		// check for client adress
		if( in_array( 'fix_adress', $this->_security ) && isset( $_SERVER['REMOTE_ADDR'] ) )
		{
			$ip	= $this->get( 'session.client.address' );

			if( $ip === null ) {
				$this->set( 'session.client.address', $_SERVER['REMOTE_ADDR'] );
			}
			else if( $_SERVER['REMOTE_ADDR'] !== $ip )
			{
				$this->_state	=	'error';
				return false;
			}
		}

		// check for clients browser
		if( in_array( 'fix_browser', $this->_security ) && isset( $_SERVER['HTTP_USER_AGENT'] ) )
		{
			$browser = $this->get( 'session.client.browser' );

			if( $browser === null ) {
				$this->set( 'session.client.browser', $_SERVER['HTTP_USER_AGENT']);
			}
			else if( $_SERVER['HTTP_USER_AGENT'] !== $browser )
			{
				//$this->_state	=	'error';
				//return false;
			}
		}

		return true;
	}
}