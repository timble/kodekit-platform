<?php
/**
 * @package     Koowa_Session
 * @subpackage  Container
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Message User Session Container Class
 *
 * Session container that stores flash messages and provides utility functions. Flash messages are self-expiring
 * messages that are meant to live for exactly one request (they're "gone in a flash"). They're designed to be used
 * across redirects.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Session
 * @subpackage  Container
 */
class UserSessionContainerMessage extends UserSessionContainerAbstract
{
    /**
     * Get all the flash messages and clears all messages
     *
     * @return array
     */
    public function all()
    {
        $result = $this->toArray();

        $this->clear();

        return $result;
    }

    /**
     * Gets flash messages for a given type and clears all messages for that type
     *
     * @param string $type    Message category type.
     * @param array  $default Default value if $type does not exist.
     * @return array
     */
    public function get($type, $default = array())
    {
        $result = parent::get($type, $default);

        $this->remove($type);

        return $result;
    }

    /**
     * Add a messages for a given type.
     *
     * @param string    $message
     * @param string    $type    Message category type. Default is 'success'.
     * @return UserSessionContainerMessage
     */
    public function add($message, $type = 'success')
    {
        if(!isset($this->_data[$type])) {
            $this->_data[$type] = array();
        }

        $this->_data[$type][] = $message;
        return $this;
    }

    /**
     * Set the messages for a given type.
     *
     * @param string       $type    Message category type.
     * @param string|array $messages
     * @return UserSessionContainerMessage
     */
    public function set($type, $messages)
    {
        parent::set($type, (array) $messages);
        return $this;
    }

    /**
     * Has flash messages for a given type?
     *
     * @param string $type  Message category type.
     * @return boolean
     */
    public function has($type)
    {
        return parent::has($type);
    }

    /**
     * Removes the flash messages for a given type
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
     * Clears out all flash messages
     *
     * @return UserSessionContainerMessage
     */
    public function clear()
    {
        parent::clear();
        return $this;
    }

    /**
     * Add flash messages
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