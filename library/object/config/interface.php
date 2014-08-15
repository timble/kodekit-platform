<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Config Interface
 *
 * ObjectConfig provides a property based interface to an array. Data is can be modified unless the object is marked
 * as readonly.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
interface ObjectConfigInterface extends \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * Retrieve a configuration option
     *
     * If the option does not exist return the default.
     *
     * @param string  $name
     * @param mixed   $default
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * Set a configuration option
     *
     * @param  string $name
     * @param  mixed  $value
     * @throws \RuntimeException If the config is read only
     * @return ObjectConfig
     */
    public function set($name, $value);

    /**
     * Check if a configuration option exists
     *
     * @param  	string 	$name The configuration option name.
     * @return  boolean
     */
    public function has($name);

    /**
     * Remove a configuration option
     *
     * @param   string $name The configuration option name.
     * @throws \RuntimeException If the config is read only
     * @return  ObjectConfig
     */
    public function remove( $name );

    /**
     * Merge options
     *
     * This method will overwrite keys that already exist, keys that don't exist yet will be added.
     *
     * For duplicate keys, the following will be performed:
     *
     * - Nested configs will be recursively merged.
     * - Items in $options with INTEGER keys will be appended.
     * - Items in $options with STRING keys will overwrite current values.
     *
     * @param  array|\Traversable|ObjectConfig  $options A ObjectConfig object an or array of options to be added
     * @throws \RuntimeException If the config is read only
     * @return ObjectConfigInterface
     */
    public function merge($options);

    /**
     * Append values
     *
     * This function only adds keys that don't exist and it filters out any duplicate values
     *
     * @param  array|\Traversable|ObjectConfig $config A value of an or array of values to be appended
     * @throws \RuntimeException If the config is read only
     * @return ObjectConfigInterface
     */
    public function append($config);

    /**
     * Unbox a ObjectConfig object
     *
     * If the data being passed is an instance of ObjectConfig the data will be transformed to an associative array.
     *
     * @param  ObjectConfigInterface|mixed $data
     * @return array|mixed
     */
    public static function unbox($data);

    /**
     * Return an associative array of the config data.
     *
     * @return array
     */
    public function toArray();

    /**
     * Return a ObjectConfig object from an array
     *
     * @param  array $array
     * @param  bool $readonly  TRUE to not allow modifications of the config data. Default FALSE.
     * @return ObjectConfig Returns a ObjectConfig object
     */
    public static function fromArray(array $array, $readonly = false);

    /**
     * Prevent any more modifications being made to this instance.
     *
     * Useful after merge() has been used to merge multiple Config objects into one object which should then not be
     * modified again.
     *
     * @return ObjectConfigInterface
     */
    public function setReadOnly();

    /**
     * Returns whether this ObjectConfig object is read only or not.
     *
     * @return bool
     */
    public function isReadOnly();

    /**
     * Get a new instance
     *
     * @return ObjectConfigInterface
     */
    public function getInstance();
}