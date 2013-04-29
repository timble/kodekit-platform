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
 * Session container that stores session flash messages and provides utility functions.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Session
 * @subpackage  Container
 */
class UserSessionContainerMessage extends UserSessionContainerAbstract
{
    /**
     * Default messages namespace
     */
    const TYPE_INFO    = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR   = 'error';

    /**
     * Gets flash messages for a given type
     *
     * @param string $type    Message category type.
     * @param array  $default Default value if $type does not exist.
     * @return array
     */
    public function get($type, $default = array())
    {
        parent::get($type, $default);
    }

    /**
     * Add a message for a given type.
     *
     * @param string       $type    Message category type.
     * @param string|array $message
     * @return UserSessionContainerMessage
     */
    public function set($type, $message)
    {
        parent::set($type, $message);
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
     * All numerical array keys will be modified to start counting from zero while literal keys won't be touched.
     *
     * Required by interface ArrayAccess
     *
     * @param   int     $offset
     * @return  void
     */
    public function offsetUnset($offset)
    {
        return ObjectArray::offsetUnset($offset);
    }
}