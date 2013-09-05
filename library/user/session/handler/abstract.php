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
 * Abstract Session Handler
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\User
 * @see         http://www.php.net/manual/en/function.session-set-save-handler.php
 */
abstract class UserSessionHandlerAbstract extends Object implements UserSessionHandlerInterface
{
    /**
     * The handler that was registered
     *
     * @var object
     * @see isRegistered()
     */
    static protected $_registered = null;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @throws \RuntimeException If the session handler is not available
     * @return UserSessionHandlerAbstract
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (!$this->isSupported())
        {
            $name = $this->getIdentifier()->name;
            throw new \RuntimeException('The ' . $name . ' session handler is not available');
        }

        //Register the functions of this class with the PHP session handler
        if ($config->auto_register) {
            $this->register();
        }
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'auto_register' => false,
        ));

        parent::_initialize($config);
    }

    /**
     * Register the functions of this class with PHP's session handler
     *
     * @see http://php.net/session-set-save-handler
     * @return void
     */
    public function register()
    {
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );

        $this->_registered = $this;
    }

    /**
     * Initialize the session handler backend
     *
     * @param   string  $save_path     The path to the session object
     * @param   string  $session_name  The name of the session
     * @return  boolean  True on success, false otherwise
     */
    public function open($save_path, $session_name)
    {
        return true;
    }

    /**
     * Close the session handler backend
     *
     * @return  boolean  True on success, false otherwise
     */
    public function close()
    {
        return true;
    }

    /**
     * Read session data for a particular session identifier from the session handler backend
     *
     * @param   string  $session_id  The session identifier
     * @return  string  The session data
     */
    public function read($session_id)
    {
        return;
    }

    /**
     * Write session data to the session handler backend
     *
     * @param   string  $session_id    The session identifier
     * @param   string  $session_data  The session data
     * @return  boolean  True on success, false otherwise
     */
    public function write($session_id, $session_data)
    {
        return true;
    }

    /**
     * Destroy the data for a particular session identifier in the session handler backend
     *
     * @param   string  $session_id  The session identifier
     * @return  boolean  True on success, false otherwise
     */
    public function destroy($session_id)
    {
        return true;
    }

    /**
     * Garbage collect stale sessions from the SessionHandler backend.
     *
     * @param   integer  $maxlifetime  The maximum age of a session
     * @return  boolean  True on success, false otherwise
     */
    public function gc($maxlifetime = null)
    {
        return true;
    }

    /**
     * Is this handler registered with the PHP's session handler
     *
     * @return boolean  True on success, false otherwise
     */
    public function isRegistered()
    {
        if (self::$_registered === $this) {
            return true;
        }

        return false;
    }

    /**
     * Test to see if the session handler is available.
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        return true;
    }
}
