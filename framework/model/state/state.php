<?php
/**
 * @package		Koowa_Model
 * @subpackage  State
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Model State Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Model
 * @subpackage  State
 */
class KModelState extends KConfig implements KModelStateInterface
{
    /**
     * Insert a new state
     *
     * @param   string   $name     The name of the state
     * @param   mixed    $filter   Filter(s), can be a KFilterInterface object, a filter name or an array of filter names
     * @param   mixed    $default  The default value of the state
     * @param   boolean  $unique   TRUE if the state uniquely identifies an entity, FALSE otherwise. Default FALSE.
     * @param   array    $required Array of required states to determine if the state is unique. Only applicable if the
     *                             state is unqiue.
     * @return  KModelState
     */
    public function insert($name, $filter, $default = null, $unique = false, $required = array())
    {
        $state = new \stdClass();
        $state->name     = $name;
        $state->filter   = $filter;
        $state->value    = $default;
        $state->unique   = $unique;
        $state->required = $required;
        $state->default  = $default;
        $this->_data[$name] = $state;

        return $this;
    }

    /**
     * Retrieve a configuration item and return $default if there is no element set.
     *
     * @param string $name      The state name
     * @param mixed  $default   The state default value if no state can be found
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $result = $default;
        if(isset($this->_data[$name])) {
            $result = $this->_data[$name]->value;
        }

        return $result;
    }

    /**
     * Set state value
     *
     * @param  	string 	$name The state name.
     * @param  	mixed  	$value The state value.
     * @return 	KModelState
     */
    public function set($name, $value)
    {
        if(isset($this->_data[$name])) {
            $this->_data[$name]->value = $value;
        }

        return $this;
    }

    /**
     * Check if a state exists
     *
     * @param  	string 	$name The state name.
     * @return  boolean
     */
    public function has($name)
    {
        $result = false;
        if(isset($this->_data[$name])) {
            $result = true;
        }

        return $result;
    }

    /**
     * Remove an existing state
     *
     * @param   string $name The name of the state
     * @return  KModelState
     */
    public function remove( $name )
    {
        unset($this->_data[$name]);
        return $this;
    }

    /**
     * Reset all state data and revert to the default state
     *
     * @param   boolean If TRUE use defaults when resetting. Default is TRUE
     * @return KModelState
     */
    public function reset($default = true)
    {
        foreach($this->_data as $state) {
            $state->value = $default ? $state->default : null;
        }

        return $this;
    }

    /**
     * Set the state data
     *
     * This function will only filter values if we have a value. If the value is an empty string it will be filtered
     * to NULL.
     *
     * @param   array An associative array of state values by name
     * @return  KModelState
     */
    public function fromArray(array $data)
    {
        // Filter data
        foreach($data as $key => $value)
        {
            if(isset($this->_data[$key]))
            {
                $filter = $this->_data[$key]->filter;

                //Only filter if we have a value
                if($value !== null)
                {
                    if($value !== '')
                    {
                        if(!($filter instanceof KFilterInterface)) {
                            $filter = KService::get('koowa:filter.factory')->instantiate($filter);
                        }

                        $value = $filter->sanitize($value);
                    }
                    else $value = null;

                    $this->_data[$key]->value = $value;
                }
            }
        }

        return $this;
    }

    /**
     * Get the state data
     *
     * This function only returns states that have been been set.
     *
     * @param   boolean If TRUE only retrieve unique state values, default FALSE
     * @return  array   An associative array of state values by name
     */
    public function toArray($unique = false)
    {
        $data = array();

        foreach ($this->_data as $name => $state)
        {
            if(isset($state->value))
            {
                //Only return unique data
                if($unique)
                {
                    //Unique values cannot be null or an empty string
                    if($state->unique && $this->_validate($state))
                    {
                        $result = true;

                        //Check related states to see if they are set
                        foreach($state->required as $required)
                        {
                            if(!$this->_validate($this->_data[$required]))
                            {
                                $result = false;
                                break;
                            }
                        }

                        //Prepare the data to be returned. Include states
                        if($result)
                        {
                            $data[$name] = $state->value;

                            foreach($state->required as $required) {
                                $data[$required] = $this->_data[$required]->value;
                            }
                        }
                    }
                }
                else $data[$name] = $state->value;
            }
        }

        return $data;
    }

	/**
     * Return an associative array of the states.
     *
     * @return array
     */
    public function getStates()
    {
        return $this->_data;
    }

    /**
     * Check if the state information is unique
     *
     * @return  boolean TRUE if the state is unique, otherwise FALSE.
     */
    public function isUnique()
    {
        $unique = false;

        //Get the unique states
        $states = $this->toArray(true);

        if(!empty($states))
        {
            $unique = true;

            //If a state contains multiple values the state is not unique
            foreach($states as $state)
            {
                if(is_array($state) && count($state) > 1)
                {
                    $unique = false;
                    break;
                }
            }
        }

        return $unique;
    }

    /**
     * Check if the state information is empty
     *
     * @param   array   An array of states names to exclude.
     * @return  boolean TRUE if the state is empty, otherwise FALSE.
     */
    public function isEmpty(array $exclude = array())
    {
        $states = $this->toArray();

        foreach($exclude as $state) {
            unset($states[$state]);
        }

        return (bool) (count($states) == 0);
    }

	/**
     * Validate a unique state.
     *
     * @param  object  The state object.
     * @return boolean True if unique state is valid, false otherwise.
     */
    protected function _validate($state)
    {
        // Unique values can't be null or empty string.
        if(empty($state->value) && !is_numeric($state->value)) {
            return false;
        }

        if(is_array($state->value))
        {
            // The first element of the array can't be null or empty string.
            $first = array_slice($state->value, 0, 1);
            if(empty($first) && !is_numeric($first)) {
                return false;
            }
        }

        return true;
    }
}