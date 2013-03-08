<?php
/**
 * @package		Koowa_Model
 * @subpackage  State
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Model State Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Model
 * @subpackage  State
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
     * @return  ModelStateInterface
     */
    public function insert($name, $filter, $default = null, $unique = false, $required = array());

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
    public function remove( $name );

    /**
     * Reset all state data and revert to the default state
     *
     * @param   boolean $default If TRUE use defaults when resetting. Default is TRUE
     * @return ModelStateInterface
     */
    public function reset($default = true);

    /**
     * Set the state data
     *
     * This function will only filter values if we have a value. If the value is an empty string it will be filtered
     * to NULL.
     *
     * @param   array An associative array of state values by name
     * @return  ModelState
     */
    public function fromArray(array $data);

    /**
     * Get the state data
     *
     * This function only returns states that have been been set.
     *
     * @param   boolean If TRUE only retrieve unique state values, default FALSE
     * @return  array   An associative array of state values by name
     */
    public function toArray($unique = false);

    /**
     * Return an associative array of the states.
     *
     * @param bool 	If TRUE return only as associative array of the state values. Default is TRUE.
     * @return array
     */
    public function getStates();

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