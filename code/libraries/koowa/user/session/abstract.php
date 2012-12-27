<?php
/**
 * @version        $Id$
 * @package        Koowa_Dispatcher
 * @subpackage  Session
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Abstract Session Class
 *
 * Provides access to session-state values as well as session-level settings and lifetime management methods.
 *
 * @author        Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Session
 */
abstract class KDispatcherSessionAbstract extends KObject implements KDispatcherSessionInterface
{
    /**
     * Is the session active
     *
     * @var boolean
     * @see isActive()
     */
    protected $_active;

    /**
     * Maximum session lifetime
     *
     * @var integer The session maximum lifetime in seconds
     * @see getExpire()
     */
    protected $_lifetime;

    /**
     * The data namespace
     *
     * @var string
     */
    protected $_namespace;

    /**
     * The session handler
     *
     * @var KDispatcherSessionHandlerInterface
     */
    protected $_handler;

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
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KDispatcherSessionAbstract
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Session write and close handlers are called after destructing objects since PHP 5.0.5.
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            session_register_shutdown();
        } else {
            register_shutdown_function('session_write_close');
        }

        //Set the session options
        $this->setOptions($config->options);

        //Set the session name
        if (!empty($config->name)) {
            $this->setName($config->name);
        }

        //Set the session identifier
        if (!empty($config->id)) {
            $this->setId($config->id);
        }

        //Set lifetime time
        $this->setLifetime($config->lifetime);

        //Set the session data namespace
        $this->setNamespace($config->namespace);

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
            'handler'   => 'file',
            'name'      => 'KSESSIONID',
            'id'        => '',
            'lifetime'  => 1440,
            'namespace' => '__default',
            'options' => array(
                'auto_start'        => 0,
                'cache_limiter'     => '',
                'use_cookies'       => 1,
                'use_only_cookies'  => 1,
                'cookie_httponly'   => 1,
                'save_handler'      => 'files',
                'use_trans_sid'     => 0,
                'entropy_file'      => '/dev/urandom',
                'entropy_length'    => 128,
                'hash_function'     => 'sha256',
                'hash_bits_per_character' => 5,
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
                ini_set('session.' . $key, $value);
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
        return new ArrayIterator($_SESSION[$this->_namespace]);
    }

    /**
     * Set the session life time
     *
     * This specifies the number of seconds after which data will expire. An expired session will be destroyed
     * automatically during session start.
     *
     * @param integer $lifetime The session lifetime in seconds
     * @return \KDispatcherSessionInterface
     */
    public function setLifetime($lifetime)
    {
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
     * Get the session life time
     *
     * @return integer The session life time in seconds
     *
     * @deprecated since 12.3, will be removed from 13.2
     */
    public function getExpire()
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
     * @throws LogicException    When changing the name of an active session
     * @return \KDispatcherSessionInterface
     */
    public function setName($name)
    {
        if ($this->isActive()) {
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
        if ($this->isActive()) {
            $id = session_id();
        }

        return $id;
    }

    /**
     * Set the session id
     *
     * @param string $session_id
     * @throws LogicException    When changing the id of an active session
     * @return \KDispatcherSessionInterface
     */
    public function setId($session_id)
    {
        if ($this->isActive()) {
            throw new LogicException('Cannot change the id of an active session');
        }

        session_id($session_id);
        return $this;
    }

    /**
     * Set the session namespace
     *
     * This specifies namespace that is used when storing or retrieving data from the session. The namespace prevents
     * session conflicts when the session is shared.
     *
     * @param string $namespace The session namespace
     * @return \KDispatcherSessionInterface
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
        return $this;
    }

    /**
     * Get the session namespace
     *
     * @return string The session namespace
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Method to set a session handler object
     *
     * @param mixed $hanlder An object that implements KObjectServiceable, KServiceIdentifier object
     *                          or valid identifier string
     * @param array $config An optional associative array of configuration settings
     * @throws \DomainException    If the identifier is not a session handler identifier
     * @return \KDispatcherSessionInterface
     */
    public function setHandler($handler, $config = array())
    {
        if (!($handler instanceof KDispatcherSessionHandlerInterface))
        {
            if (is_string($handler) && strpos($handler, '.') === false)
            {
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('session', 'handler');
                $identifier->name = $handler;
            }
            else $identifier = $this->getIdentifier($handler);

            if ($identifier->path[1] != 'handler') {
                throw new DomainException('Identifier: ' . $identifier . ' is not a session handler identifier');
            }

            $handler = $this->_handler = $this->getService($identifier, $config);
        }

        $this->_handler = $handler;
        return $this;
    }

    /**
     * Get the session handler object
     *
     * @return    KDispatcherSessionHandlerInterface
     */
    public function getHandler()
    {
        return $this->_handler;
    }

    /**
     * Get a session token, if a token isn't set yet one will be generated.
     *
     * @param   boolean $refresh If true, force a new token to be created
     * @return  string  The session token
     */
    public function getToken($refresh = false)
    {
        if ($this->_token === null || $refresh) {
            $this->_token = $this->_createToken(12);
        }

        return $this->_token;
    }

    /**
     * Get the session ip address
     *
     * The IP address from which the user. Stored when the session is started or regenerated.
     *
     * @param   boolean $refresh If true, the address will be updated based on the current request
     * @return  string  The session ip address
     */
    public function getAddress($refresh = false)
    {
        if ($this->_address === null || $refresh)
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $this->_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (isset($_SERVER['REMOTE_ADDR'])) {
                $this->_address = $_SERVER['REMOTE_ADDR'];
            } else {
                $this->_address = '';
            }
        }

        return $this->_address;
    }

    /**
     * Get the session user agent
     *
     * Contents of the User-Agent: header, if there is one. Stored when the session is started or regenerated.
     *
     * @param   boolean $refresh If true, the agent will be updated based on the current request
     * @return  string  The session user agent
     */
    public function getAgent($refresh = false)
    {
        if ($this->_agent === null || $refresh)
        {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $this->_agent = $_SERVER['HTTP_USER_AGENT'];
            } else {
                $this->_agent = '';
            }
        }

        return $this->_agent;
    }

    /**
     * Is this session active
     *
     * @return boolean  True on success, false otherwise
     */
    public function isActive()
    {
        $sid = defined('SID') ? constant('SID') : false;
        if ($sid !== false && session_id()) {
            return true;
        }

        if (headers_sent()) {
            return true;
        }

        return false;
    }

    /**
     * Starts the session storage and load the session data into memory
     *
     * @see  session_start()
     * @return \KDispatcherSessionInterface
     * @throws \RuntimeException If something goes wrong starting the session.
     */
    public function start()
    {
        if (!$this->isActive())
        {
            //Make sure we have a registered session handler
            if (!$this->getHandler()->isRegistered()) {
                $this->getHandler()->register();
            }

            session_cache_limiter('none');

            if (ini_get('session.use_cookies') && headers_sent()) {
                throw new RuntimeException('Failed to start the session because headers have already been sent');
            }

            if (!session_start()) {
                throw new RuntimeException('Session could not be started');
            }

            //Add the namespace
            $this->{$this->_namespace} = array();

            //Update the session timers
            $this->_updateTimers();

            $curTime = $this->__timer['now'];
            $maxTime = $this->__timer['last'] + $this->getLifetime();

            // Destroy an expired session
            if ($maxTime < $curTime) {
                $this->destroy();
            }
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
    public function close()
    {
        //Write the session data and close the session
        if ($this->isActive()) {
            session_write_close();
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
        if ($this->isActive())
        {
            // In order to kill the session altogether, like to log the user out, the session id must also be unset. If
            // a cookie is used to propagate the session id (default behavior) then the session cookie must be deleted.
            if (ini_get("session.use_cookies") && isset($_COOKIE[$this->getName()]))
            {
                $params = session_get_cookie_params();
                setcookie($this->getName(), '', time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }

            //Clear the session variable
            $this->clear();

            //Destroy the session
            session_destroy();
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
        if ($this->isActive())
        {
            if ($lifetime !== null) {
                ini_set('session.cookie_lifetime', $lifetime);
            }

            if (!session_regenerate_id($destroy)) {
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
        return count($_SESSION[$this->_namespace]);
    }

    /**
     * Get a session value by key
     *
     * @param   string $key   The key name.
     * @return  string $value The corresponding value.
     */
    public function &__get($key)
    {
        $result = null;
        if (isset($_SESSION[$this->_namespace][$key])) {
            $result = $_SESSION[$this->_namespace][$key];
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
        $_SESSION[$this->_namespace][$key] = $value;
    }

    /**
     * Test existence of a key
     *
     * @param  string $key The key name.
     * @return boolean
     */
    public function __isset($key)
    {
        return array_key_exists($key, $_SESSION[$this->_namespace]);
    }

    /**
     * Unset a key
     *
     * @param   string $key  The key name.
     * @return  void
     */
    public function __unset($key)
    {
        unset($_SESSION[$this->_namespace][$key]);
    }

    /**
     * Return an associative array of the session data.
     *
     * @return array
     */
    public function toArray()
    {
        return $_SESSION[$this->_namespace];
    }

    /**
     * Create a token-string
     *
     * @param   integer $length Length of string
     * @return  string  Generated token
     */
    protected function _createToken($length = 32)
    {
        static $chars = '0123456789abcdef';

        $max = strlen($chars) - 1;
        $token = '';
        $name = $this->getName();

        for ($i = 0; $i < $length; ++$i) {
            $token .= $chars[(rand(0, $max))];
        }

        return md5($token . $name);
    }

    /**
     * Update the session timers
     *
     * @return  void
     */
    protected function _updateTimers()
    {
        if (!isset($this->__timer))
        {
            $start = time();

            $timer = array(
                'start' => $start,
                'last' => $start,
                'now' => $start
            );

            $timer;
        }
        else $timer = $this->__timer;

        $timer['last'] = $timer['now'];
        $timer['now'] = time();

        $this->__timer = $timer;
    }
}