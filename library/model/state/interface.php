<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Model State Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Model
 */
interface ModelStateInterface
{
    /**
     * Insert a new state
     *
     * @param   string   $name     The name of the state
     * @param   mixed    $filter   Filter(s), can be a FilterInterface object, a filter name or an array of filter names
     * @param   mixed    $default  The default value of the state
     * @param   boolean  $unique   TRUE if the state uniquely identifies an entity, FALSE otherwise. Default FALSE.
     * @param   array    $required Array of required states to determine if the state is unique. Only applicable if the state is unqiue.
     * @param   boolean  $internal If TRUE the state will be considered internal and should not be included in a routes.
     *                             Default FALSE.
     * @return  ModelStateInterface
     */
    public function insert($name, $filter, $default = null, $unique = false, $required = array(), $internal = false);

    /**
     * Retrieve a configuration item and return $default if there is no element set.
     *
     * @param string $name      The state name
     * @param mixed  $default   The state default value if no state can be found
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * Set state value
     *
     * @param  	string 	$name The state name.
     * @param  	mixed  	$value The state value.
     * @return 	ModelStateInterface
     */
    public function set($name, $value);

    /**
     * Check if a state exists
     *
     * @param  	string 	$name The state name.
     * @return  boolean
     */
    public function has($name);

    /**
     * Remove an existing state
     *
     * @param   string $name The name of the state
     * @return  ModelStateInterface
     */
    public function remove($name);

    /**
     * Reset all state data and revert to the default state
     *
     * @return ModelStateInterface
     */
    public function reset();

    /**
     * Get the total number of entities
     *
     * @return  int
     */
    public function count();

    /**
     * Set the state data
     *
     * This function will only filter values if we have a value. If the value is an empty string it will be filtered
     * to NULL.
     *
     * @param   array $data An associative array of state values by name
     * @return  ModelState
     */
    public function setValues(array $data);

    /**
     * Get the state data
     *
     * This function only returns states that have been been set.
     *
     * @param   boolean $unqique If TRUE only retrieve unique state values, default FALSE
     * @return  array   An associative array of state values by name
     */
    public function getValues($unique = false);

    /**
     * Set a state property
     *
     * @param string $name      The name of the state
     * @param string $property  The name of the property
     * @param mixed  $value     The value of the property
     * @return ModelStateInterface
     */
    public function setProperty($name, $property, $value);

    /**
     * Get a state property
     *
     * @param string $name     The name of the state
     * @param string $property The name of the property
     * @return mixed
     */
    public function getProperty($name, $property);

    /**
     * Check if a state property exists
     *
     * @param string $name     The name of the state
     * @param string $property The name of the property
     * @return boolean   Return TRUE if the the property exists, FALSE otherwise
     */
    public function hasProperty($name, $property);

    /**
     * Check if the state information is unique
     *
     * @return  boolean TRUE if the state is unique, otherwise FALSE.
     */
    public function isUnique();

    /**
     * Check if the state information is empty
     *
     * @param   array   $exclude An array of states names to exclude.
     * @return  boolean TRUE if the state is empty, otherwise FALSE.
     */
    public function isEmpty(array $exclude = array());
}