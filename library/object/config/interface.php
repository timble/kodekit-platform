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
 * Object Config Interface
 *
 * ObjectConfig provides a property based interface to an array
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
interface ObjectConfigInterface extends \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * Retrieve a configuration option
     *
     * If the option does not exist return the default
     *
     * @param string
     * @param mixed
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * Set a configuration option
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
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
     * @return  ObjectConfigInterface
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
     * @param  array|ObjectConfig  $options A ObjectConfig object an or array of options to be added
     * @return ObjectConfigInterface
     */
    public function merge($options);

    /**
     * Append values
     *
     * This function only adds keys that don't exist and it filters out any duplicate values
     *
     * @param  mixed $config A value of an or array of values to be appended
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
     * @return ObjectConfig Returns a ObjectConfig object
     */
    public static function fromArray(array $array);
}