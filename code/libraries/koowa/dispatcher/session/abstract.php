<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @subpackage  Session
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Session Class
 *
 * Provides access to session-state values as well as session-level settings and lifetime management methods.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Session
 */
abstract class KDispatcherSessionAbstract extends KObject implements KDispatcherSessionInterface
{
    /**
     * The internal session state
     *
     * @var string
     * @see getState()
     */
    protected $_state = '';

    /**
     * Maximum session lifetime
     *
     * @var integer The session maximum lifetime in seconds
     * @see getExpire()
     */
    protected $_lifetime = 1440;

    /**
     * The session handler
     *
     * @var KDispatcherSessionHandlerInterface
     */
    protected $_handler =  null;

    /**
     * Valid session config options
     *
     * @var array
     * @see http://php.net/session.configuration
     */
    protected static $_valid_options = array(
        'auto_start',
        'cache_limiter',
        'cookie_domain',
        'cookie_httponly',
        'cookie_lifetime',
        'cookie_path',
        'cookie_secure',
        'entropy_file',
        'entropy_length',
        'gc_divisor',
        'gc_maxlifetime',
        'gc_probability',
        'hash_bits_per_character',
        'hash_function',
        'name',
        'referer_check',
        'serialize_handler',
        'use_cookies',
        'use_only_cookies',
        'use_trans_sid',
        'upload_progress.enabled',
        'upload_progress.cleanup',
        'upload_progress.prefix',
        'upload_progress.name',
        'upload_progress.freq',
        'upload_progress.min-freq',
        'url_rewriter.tags',
    );

    /**
     * Session states
     */
    const ACTIVE   = 'active';
    const NONE     = 'none';
    const DISABLED = 'disabled';

    /**
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KDispatcherSessionAbstract
     */
    public function __construct( KConfig $config = null )
    {
        //If no config is passed create it
        if(!isset($config)) $config = new KConfig();

        parent::__construct($config);

        //Session write and close handlers are called after destructing objects since PHP 5.0.5.
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            session_register_shutdown();
        } else {
            register_shutdown_function('session_write_close');
        }

        //Set the sesssion options
        $this->setOptions($config->options);

        //Set the session name
        if(!empty($config->name)) {
            $this->setName($config->name);
        }

        //Set the session identifier
        if(!empty($config->id)) {
            $this->setId($config->id);
        }

        //Set lifetime time
        if(!empty($config->lifetime)) {
            $this->setLifetime($config->lifetime);
        }

        //Set the session handler
        $this->setHandler($config->handler, KConfig::unbox($config));
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation
     *
     * @param   KConfig $object An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'handler'  => 'file',
            'name'     => 'KSESSIONID',
            'id'       =>  '',
            'lifetime' => 1440,
            'options'  => array(
                'auto_start'    => 0,
                'cache_limiter' => '',
                'use_cookies'   => 1,
                'save_handler'  => 'files',
                'use_trans_sid' => 0
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Sets session.* ini variables
     *
     * For convenience we omit 'session.' from the beginning of the keys. Explicitly ignores other ini keys.
     *
     * @param array $options Session ini directives array(key => value)
     * @see http://php.net/session.configuration
     */
    public function setOptions($options)
    {
        $valid = array_flip(self::$_valid_options);

        //Sets session.* ini variables.
        foreach ($options as $key => $value)
        {
            if (isset($valid[$key])) {
                ini_set('session.'.$key, $value);
            }
        }
    }

    /**
     * Get a new iterator
     *
     * @return  ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($_SESSION);
    }

    /**
     * Set the session life time
     *
     * This specifies the number of seconds after which data will be seen as 'garbage' and potentially cleaned up.
     * Garbage collection may occur during session start.
     *
     * @param integer $lifetime The session lifetime in seconds
     * @return \KDispatcherSessionInterface
     */
    public function setLifetime($lifetime)
    {
        //Sync the session maxlifetime
        ini_set('session.gc_maxlifetime', $lifetime);

        $this->_lifetime = $lifetime;
        return $this;
    }

    /**
     * Get the session life time
     *
     * @return integer The session life time in seconds
     */
    public function getLifetime()
    {
        return $this->_lifetime;
    }

    /**
     * Get the session name
     *
     * @return string The session name
     */
    public function getName()
    {
        return session_name();
    }

    /**
     * Set the session name
     *
     * @param  string $name
     * @throws LogicException	When changing the name of an active session
     * @return \KDispatcherSessionInterface
     */
    public function setName($name)
    {
        if ($this->_state == self::ACTIVE) {
            throw new LogicException('Cannot change the name of an active session');
        }

        session_name($name);
        return $this;
    }

    /**
     * Get the session id
     *
     * @return string The session id
     */
    public function getId()
    {
        $id = ''; // returning empty is consistent with session_id() behaviour
        if ($this->_state == self::ACTIVE) {
            $id = session_id();
        }

        return $id;
    }

    /**
     * Set the session id
     *
     * @param string $session_id
     * @throws LogicException	When changing the id of an active session
     * @return \KDispatcherSessionInterface
     */
    public function setId($session_id)
    {
        if ($this->_state == self::ACTIVE) {
            throw new LogicException('Cannot change the id of an active session');
        }

        session_id($session_id);
        return $this;
    }

    /**
     * Get current state of session
     *
     * @return string The session state
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * Method to set a session handler object
     *
     * @param mixed $hanlder An object that implements KObjectServiceable, KServiceIdentifier object
     * 					     or valid identifier string
     * @param array $config An optional associative array of configuration settings
     * @throws KDispatcherSessionException	If the identifier is not a session handler identifier
     * @return \KDispatcherSessionInterface
     */
    public function setHandler($handler, $config = array())
    {
        if(!($handler instanceof KDispatcherSessionHandlerInterface))
        {
            if(is_string($handler) && strpos($handler, '.') === false )
            {
                $identifier		   = clone $this->getIdentifier();
                $identifier->path  = array('session', 'handler');
                $identifier->name  = $handler;
            }
            else $identifier = $this->getIdentifier($handler);

            if($identifier->path[1] != 'handler') {
                throw new DomainException('Identifier: '.$identifier.' is not a session handler identifier');
            }

            $handler = $this->_handler = $this->getService($identifier, $config);
        }

        $this->_handler = $handler;
        return $this;
    }

    /**
     * Get the session handler object
     *
     * @return	KDispatcherSessionHandlerInterface
     */
    public function getHandler()
    {
        return $this->_handler;
    }

    /**
     * Get a session token, if a token isn't set yet one will be generated.
     *
     * Tokens are used to secure forms from spamming attacks. Once a token has been generated a call to verifyToken will
     * check if the token matches, if not the session will be expired.
     *
     * @param   boolean $forceNew If true, force a new token to be created
     * @return  string  The session token
     */
    public function getToken($forceNew = false)
    {
        $token = $this->token;

        //create a token
        if($token === null || $forceNew)
        {
            $token = $this->_createToken(12);
            $this->token = $token;
        }

        return $token;
    }

    /**
     * Method to determine if a token exists in the session. If not the session will be destroyed
     *
     * @param  string  $token         Token to be verified
     * @param  boolean $forceDestroy  If true, force destruction of the session storage if the token cannot be found
     * @param  boolean True on success, false on failure.
     */
    public function hasToken($token, $forceDestroy = true)
    {
        if($token !== $this->token)
        {
            if($forceDestroy) {
                $this->destroy();
            }

            return false;
        }

        return true;
    }

    /**
     * Is this session active
     *
     * @return boolean  True on success, false otherwise
     */
    public function isActive()
    {
        if($this->_state == self::ACTIVE) {
            return true;
        }

        return false;
    }

    /**
     * Load all the session data into memory
     *
     * @see  session_start()
     * @return \KDispatcherSessionInterface
     * @throws \KDispatcherSessionException If something goes wrong starting the session.
     */
    public function load()
    {
        if($this->_state == self::NONE)
        {
            //Make sure we have a registered session handler
            if(!$this->getHandler()->isRegistered()) {
                $this->getHandler()->register();
            }

            session_cache_limiter('none');

            if (ini_get('session.use_cookies') && headers_sent()) {
                throw new RuntimeException('Failed to start the session because headers have already been sent');
            }

            if(!session_start()) {
                throw new RuntimeException('Session could not be started');
            }

            //Set the session state
            $this->_state = self::ACTIVE;
        }

        return $this;
    }

    /**
     * Force the session to be saved and closed.
     *
     * Session data is usually stored after your script terminated without the need to call KDispatcherSession::save(),
     * but as session data is locked to prevent concurrent writes only one script may operate on a session at any time.
     *
     * When using framesets together with sessions you will experience the frames loading one by one due to this locking.
     * You can reduce the time needed to load all the frames by ending the session as soon as all changes to session
     * variables are done.
     *
     * @see  session_write_close()
     * @return \KDispatcherSessionInterface
     */
    public function save()
    {
        if($this->_state == self::ACTIVE)
        {
            session_write_close();

            //Set the session state
            $this->_state = self::NONE;
        }

        return $this;
    }

    /**
     * Clear all session data in memory.
     *
     * @see session_unset()
     * @return \KDispatcherSessionInterface
     */
    public function clear()
    {
        session_unset();
        return $this;
    }

    /**
     * Frees all session variables and destroys all data registered to a session
     *
     * This method resets the $_SESSION variable and destroys all of the data associated with the current session in its
     * storage (file or DB). It forces new session to be started after this method is called. It does also unset the
     * session cookie.
     *
     * @see session_destroy()
     * @return \KDispatcherSessionInterface
     */
    public function destroy()
    {
        // session was already destroyed
        if($this->_state == self::ACTIVE)
        {
            // In order to kill the session altogether, like to log the user out, the session id must also be unset. If
            // a cookie is used to propagate the session id (default behavior) then the session cookie must be deleted.
            if (ini_get("session.use_cookies") && isset($_COOKIE[$this->getName()]))
            {
                $params = session_get_cookie_params();
                setcookie($this->getName(), '', time() - 42000,
                    $params["path"]    ,
                    $params["domain"]  ,
                    $params["secure"]  ,
                    $params["httponly"]
                );
            }

            //Clear the session variable
            $this->clear();

            //Destroy the session
            session_destroy();

            $this->_state = self::NONE;
        }

        return $this;
    }

    /**
     * Migrates the current session to a new session id while maintaining all session data
     *
     * Note : fork and destroy should not clear the session data in memory only delete the session data from
     * persistent storage.
     *
     * @param Boolean $destroy  If TRUE, destroy session when forking.
     * @param integer $lifetime Sets the cookie lifetime for the session cookie. A null value will leave the system
     *                          settings unchanged, 0 sets the cookie to expire with browser session. Time is in seconds,
     *                          and is not a Unix timestamp.
     * @see  session_regenerate_id()
     * @return \KDispatcherSessionInterface
     * @throws \KDispatcherSessionException If an error occurs while forking this storage
     */
    public function fork($destroy = true, $lifetime = null)
    {
        if($this->_state == self::ACTIVE)
        {
            if ($lifetime !== null) {
                ini_set('session.cookie_lifetime', $lifetime);
            }

            if(!session_regenerate_id($destroy)) {
                throw new RuntimeException('Session could not be forked');
            }
        }

        return $this;
    }

    /**
     * Returns the number of session attributes
     *
     * Required by interface Countable
     *
     * @return int The number of attributes
     */
    public function count()
    {
        return count($_SESSION);
    }

    /**
     * Get a session value by key
     *
     * @param   string $key   The key name.
     * @return  string $value The corresponding value.
     */
    public function __get($key)
    {
        $result = null;
        if(isset($_SESSION[$key])) {
            $result = $_SESSION[$key];
        }

        return $result;
    }

    /**
     * Set a session value by key
     *
     * @param   string $key   The key name.
     * @param   mixed  $value The value for the key
     * @return  void
     */
    public function __set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Test existence of a key
     *
     * @param  string $key The key name.
     * @return boolean
     */
    public function __isset($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Unset a key
     *
     * @param   string $key  The key name.
     * @return  void
     */
    public function __unset($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Return an associative array of the session data.
     *
     * @return array
     */
    public function toArray()
    {
        return $_SESSION;
    }

    /**
     * Create a token-string
     *
     * @param   integer $length Lenght of string
     * @return  string  Generated token
     */
    protected function _createToken( $length = 32 )
    {
        static $chars = '0123456789abcdef';

        $max    = strlen( $chars ) - 1;
        $token  = '';
        $name   = $this->getName();

        for($i = 0; $i < $length; ++$i) {
            $token .=  $chars[ (rand( 0, $max ))];
        }

        return md5($token.$name);
    }
}