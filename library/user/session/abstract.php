<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract User Session
 *
 * Provides access to session-state values as well as session-level settings and lifetime management methods.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\User
 */
class UserSessionAbstract extends Object implements UserSessionInterface
{
    /**
     * Is the session active
     *
     * @var boolean
     * @see isActive()
     */
    protected $_active;

    /**
     * The session handler
     *
     * @var UserSessionHandlerInterface
     */
    protected $_handler;

    /**
     * The session storage
     *
     * @var array
     */
    protected $_containers = array();

    /**
     * The namespace
     *
     * @var string
     */
    protected $_namespace;

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
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return UserSession
     */
    public function __construct(ObjectConfig $config)
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

        //Set the session namespace
        $this->setNamespace($config->namespace);

        //Set lifetime time
        $this->getContainer('metadata')->setLifetime($config->lifetime);

        //Set the session handler
        $this->setHandler($config->handler, ObjectConfig::unbox($config));
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'handler'    => 'file',
            'user'       => null,
            'name'       => 'KSESSIONID',
            'id'         => '',
            'lifetime'   => 1440,
            'namespace'  => '__nooku',
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
            ),

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
     * Set the session life time
     *
     * This specifies the number of seconds after which data will expire. An expired session will be destroyed
     * automatically during session start.
     *
     * @param integer $lifetime The session lifetime in seconds
     * @return UserSessionContainerMetadata
     */
    public function setLifetime($lifetime)
    {
        $this->getContainer('metadata')->setLifetime($lifetime);
        return $this;
    }

    /**
     * Get the session life time
     *
     * @return integer The session life time in seconds
     */
    public function getLifetime()
    {
        return  $this->getContainer('metadata')->getLifetime();
    }

    /**
     * Get a session token, if a token isn't set yet one will be generated.
     *
     * @param   boolean $refresh If true, force a new token to be created
     * @return  string  The session token
     */
    public function getToken($refresh = false)
    {
        return $this->getContainer('metadata')->getToken($refresh);
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
     * @throws \LogicException    When changing the name of an active session
     * @return UserSession
     */
    public function setName($name)
    {
        if ($this->isActive()) {
            throw new \LogicException('Cannot change the name of an active session');
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
     * @throws \LogicException    When changing the id of an active session
     * @return UserSession
     */
    public function setId($session_id)
    {
        if ($this->isActive()) {
            throw new \LogicException('Cannot change the id of an active session');
        }

        session_id($session_id);
        return $this;
    }

    /**
     * Set the global session namespace
     *
     * This specifies namespace that is used when storing or retrieving attributes from the $_SESSION global. The
     * namespace prevents session conflicts when the session is shared.
     *
     * @param string $namespace The session namespace
     * @throws \LogicException When changing the namespace of an active session
     * @return UserSession
     */
    public function setNamespace($namespace)
    {
        if ($this->isActive()) {
            throw new \LogicException('Cannot change the name of an active session');
        }

        //Set the global session namespace
        $this->_namespace = $namespace;

        foreach($this->_containers as $name => $container ) {
            $container->setNamespace($namespace.'_'.$name);
        }

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
     * @param mixed $handler An object that implements UserSessionHandlerInterface, ObjectIdentifier object
     *                       or valid identifier string
     * @param array $config An optional associative array of configuration settings
     * @return UserSession
     */
    public function setHandler($handler, $config = array())
    {
        if (!($handler instanceof UserSessionHandlerInterface))
        {
            if (is_string($handler) && strpos($handler, '.') === false)
            {
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('session', 'handler');
                $identifier->name = $handler;
            }
            else $identifier = $this->getIdentifier($handler);

            //Set the configuration
            $identifier->setConfig($config);

            $handler = $identifier;
        }

        $this->_handler = $handler;
        return $this;
    }

    /**
     * Get the session handler object
     *
     * @throws \UnexpectedValueException    If the identifier is not a session handler identifier
     * @return UserSessionHandlerInterface
     */
    public function getHandler()
    {
        if(!$this->_handler instanceof UserSessionHandlerInterface)
        {
            $this->_handler = $this->getObject($this->_handler);

            if(!$this->_handler instanceof UserSessionHandlerInterface)
            {
                throw new \UnexpectedValueException(
                    'Handler: '.get_class($this->_handler).' does not implement UserSessionHandlerInterface'
                );
            }
        }

        return $this->_handler;
    }

    /**
     * Check if a container exists
     *
     * @param   string  $name  The name of the behavior
     * @return  boolean TRUE if the behavior exists, FALSE otherwise
     */
    public function hasContainer($name)
    {
        return isset($this->_containers[$name]);
    }

    /**
     * Get the session attribute container object
     *
     * If the container does not exist a container will be created on the fly.
     *
     * @param   mixed $name An object that implements ObjectInterface, ObjectIdentifier object
     *                      or valid identifier string
     * @throws \UnexpectedValueException    If the identifier is not a session container identifier
     * @return UserSessionContainerInterface
     */
    public function getContainer($name)
    {
        if (!($name instanceof ObjectIdentifier))
        {
            //Create the complete identifier if a partial identifier was passed
            if (is_string($name) && strpos($name, '.') === false)
            {
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('session', 'container');
                $identifier->name = $name;
            }
            else $identifier = $this->getIdentifier($name);
        }
        else $identifier = $name;

        if (!isset($this->_containers[$identifier->name]))
        {
            $namespace = $this->getNamespace().'_'.$identifier->name;
            $container = $this->getObject($identifier, array('namespace' => $namespace));

            if (!($container instanceof UserSessionContainerInterface))
            {
                throw new \UnexpectedValueException(
                    'Container: '. get_class($container) .' does not implement UserSessionContainerInterface'
                );
            }

            $this->_containers[$container->getIdentifier()->name] = $container;
        }
        else $container = $this->_containers[$identifier->name];

        return $container;
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
     * @throws \RuntimeException If something goes wrong starting the session.
     * @return UserSession
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
                throw new \RuntimeException('Failed to start the session because headers have already been sent');
            }

            if (!session_start()) {
                throw new \RuntimeException('Session could not be started');
            }

            //Re-load the session containers
            foreach($this->_containers as $container) {
                $container->loadSession();
            }

            // Destroy an expired session
            if ($this->getContainer('metadata')->isExpired()) {
                $this->destroy();
            }
        }

        return $this;
    }

    /**
     * Force the session to be saved and closed.
     *
     * Session data is usually stored after your script terminated without the need to call UserSession::close(),
     * but as session data is locked to prevent concurrent writes only one script may operate on a session at any time.
     *
     * When using framesets together with sessions you will experience the frames loading one by one due to this locking.
     * You can reduce the time needed to load all the frames by ending the session as soon as all changes to session
     * variables are done.
     *
     * @see  session_write_close()
     * @return UserSession
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
     * @return UserSession
     */
    public function clear()
    {
        session_unset();

        //Clear out the session data
        $_SESSION = array();

        //Re-load the session containers
        foreach($this->_containers as $container) {
            $container->loadSession();
        }

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
     * @return UserSession
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
     * Note : fork should not clear the session data in memory only delete the session data from persistent storage.
     *
     * @param Boolean $destroy  If TRUE, destroy session when forking.
     * @param integer $lifetime Sets the cookie lifetime for the session cookie. A null value will leave the system
     *                          settings unchanged, 0 sets the cookie to expire with browser session. Time is in seconds,
     *                          and is not a Unix timestamp.
     * @see  session_regenerate_id()
     * @throws \RuntimeException If an error occurs while forking this storage
     * @return UserSession
     */
    public function fork($destroy = true, $lifetime = null)
    {
        if ($this->isActive())
        {
            if ($lifetime !== null) {
                ini_set('session.cookie_lifetime', $lifetime);
            }

            if (!session_regenerate_id($destroy)) {
                throw new \RuntimeException('Session could not be forked');
            }
        }

        return $this;
    }

    /**
     * Get a session attribute
     *
     * @param   string  $identifier Attribute identifier, eg .foo.bar
     * @param   mixed   $default    Default value when the attribute doesn't exist
     * @return  mixed   The value
     */
    public function get($identifier, $default = null)
    {
        return $this->getContainer('attribute')->get($identifier, $default);
    }

    /**
     * Set a session attribute
     *
     * @param   mixed   $identifier Attribute identifier, eg foo.bar
     * @param   mixed   $value      Attribute value
     * @return User
     */
    public function set($identifier, $value)
    {
        return $this->getContainer('attribute')->set($identifier, $value);
    }

    /**
     * Check if a session attribute exists
     *
     * @param   string  $identifier Attribute identifier, eg foo.bar
     * @return  boolean
     */
    public function has($identifier)
    {
        return $this->getContainer('attribute')->has($identifier);
    }

    /**
     * Removes an session attribute
     *
     * @param string $identifier Attribute identifier, eg foo.bar
     * @return UserSession
     */
    public function remove($identifier)
    {
        $this->getContainer('attribute')->remove($identifier);
        return $this;
    }

    /**
     * Get a session attribute
     *
     * @param   string $name  The attribute name.
     * @return  string $value The attribute value.
     */
    public function __get($name)
    {
        return $this->getContainer('attribute')->get($name);
    }

    /**
     * Set a session attribute
     *
     * @param   string $name  The attribute name.
     * @param   mixed  $value The attribute value.
     * @return  void
     */
    public function __set($name, $value)
    {
        $this->getContainer('attribute')->set($name, $value);
    }

    /**
     * Test existence of a session attribute
     *
     * @param  string $name The attribute name.
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->getContainer('attribute')->has($name);
    }

    /**
     * Unset a session attribute
     *
     * @param   string $key  The attribute name.
     * @return  void
     */
    public function __unset($name)
    {
        $this->getContainer('attribute')->remove($name);
    }
}