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
 * Message User Session Container
 *
 * Session container that stores flash messages and provides utility functions. Flash messages are self-expiring
 * messages that are meant to live for exactly one request (they're "gone in a flash"). They're designed to be used
 * across redirects.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\User
 */
class UserSessionContainerMessage extends UserSessionContainerAbstract
{
    /**
     * The previous flash messages
     *
     * @var array
     */
    protected $_previous = array();

    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  UserSessionContainerAbstract
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_previous = $this->toArray();
        $this->clear();
    }

    /**
     * Get all the previous flash messages and flush them from the container
     *
     * @return array
     */
    public function all()
    {
        $result = $this->_previous;
        $this->_previous = array();

        return $result;
    }

    /**
     * Get previous flash messages for a given type and flush them from the container
     *
     * @param string $type    Message category type.
     * @param array  $default Default value if $type does not exist.
     * @return array
     */
    public function get($type, $default = array())
    {
        if(isset($this->_previous[$type]))
        {
            $result = $this->_previous[$type];
            unset($this->_previous[$type]);
        }
        else $result = $default;

        return $result;
    }

    /**
     * Add a new message for a given type.
     *
     * @param string    $message
     * @param string    $type    Message category type. Default is 'success'.
     * @return UserSessionContainerMessage
     */
    public function add($message, $type = 'success')
    {
        $this->set($type, $message);
        return $this;
    }

    /**
     * Set current flash messages for a given type.
     *
     * @param string       $type    Message category type.
     * @param string|array $messages
     * @return UserSessionContainerMessage
     */
    public function set($type, $messages)
    {
        foreach((array) $messages as $message) {
            parent::set($type, $message);
        }

        return $this;
    }

    /**
     * Has current flash messages for a given type?
     *
     * @param string $type  Message category type.
     * @return boolean
     */
    public function has($type)
    {
        return parent::has($type);
    }

    /**
     * Removes current flash messages for a given type
     *
     * @param string $type  Message category type.
     * @return UserSessionContainerMessage
     */
    public function remove($type)
    {
        parent::remove($type);
        return $this;
    }

    /**
     * Clears out all current flash messages
     *
     * @return UserSessionContainerMessage
     */
    public function clear()
    {
        parent::clear();
        return $this;
    }

    /**
     * Add new flash messages
     *
     * @param array $messages An of messages per type
     * @return UserSessionContainerMessage
     */
    public function values(array $messages)
    {
        parent::values($messages);
        return $this;
    }

    /**
     * Returns a list of all defined types.
     *
     * @return array
     */
    public function types()
    {
        return array_keys($this->_data);
    }
    /**
     * Get an item from the array by offset
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset
     * @return  mixed The item from the array
     */
    public function offsetGet($offset)
    {
        return ObjectArray::offsetGet($offset);
    }

    /**
     * Set an item in the array
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset
     * @param   mixed   $value
     * @return  void
     */
    public function offsetSet($offset, $value)
    {
        if(!isset($this->_data[$offset])) {
            $this->_data[$offset] = array();
        }

        $this->_data[$offset][] = $value;
    }

    /**
     * Check if the offset exists
     *
     * Required by interface ArrayAccess
     *
     * @param   int   $offset
     * @return  bool
     */
    public function offsetExists($offset)
    {
        return ObjectArray::offsetExists($offset);
    }

    /**
     * Unset an item in the array
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset
     * @return  void
     */
    public function offsetUnset($offset)
    {
        ObjectArray::offsetUnset($offset);
    }
}