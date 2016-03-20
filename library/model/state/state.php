<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Model State Class
 *
 * A state requires a model object. It will call back to the model upon a state change by calling reset method.
 *
 * State values can only be of type scalar or array. Values are only filtered if not NULL. If the value is an empty
 * string it will be filtered to NULL. Values will only be set if the state exists. To insert new states use the
 * the insert() function.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Model
 */
class ModelState extends ObjectArray implements ModelStateInterface
{
    /**
     * Model object
     *
     * @var string|object
     */
    private $__model;

    /**
     * Default state values
     *
     * @var array
     */
    private $__defaults;

    /**
     * Constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     * @throws \UnexpectedValueException    If no 'model' config option was passed
     * @throws \InvalidArgumentException    If the model config option does not implement ModelInterface
     * @return \Kodekit\Library\ModelState
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (empty($config->model))
        {
            throw new \InvalidArgumentException(
                'model [ModelInterface] config option is required'
            );
        }

        if(!$config->model instanceof ModelInterface)
        {
            throw new \UnexpectedValueException(
                'Model: '.get_class($config->model).' does not implement ModelInterface'
            );
        }

        $this->__model    = $config->model;
        $this->__defaults = $config->defaults;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'model'    => null,
            'defaults' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Insert a new state
     *
     * @param   string   $name     The name of the state
     * @param   mixed    $filter   Filter(s), can be a FilterInterface object, a filter name or an array of filter names
     * @param   mixed    $default  The default value of the state
     * @param   boolean  $unique   TRUE if the state uniquely identifies an entity, FALSE otherwise. Default FALSE.
     * @param   array    $required Array of required states to determine if the state is unique. Only applicable if the
     *                             state is unqiue.
     * @param   boolean  $internal If TRUE the state will be considered internal and should not be included in a routes.
     *                             Default FALSE.
     * @return  ModelState
     */
    public function insert($name, $filter, $default = null, $unique = false, $required = array(), $internal = false)
    {
        //Create the state
        $state = new \stdClass();
        $state->name     = $name;
        $state->filter   = $filter;
        $state->unique   = $unique;
        $state->required = $required;
        $state->default  = isset($this->__defaults[$name]) ? $this->__defaults[$name] : $default;
        $state->value    = $state->default;
        $state->internal = $internal;

        //Sanitize the state
        $this->_data[$name] = $this->_sanitize($state);

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
        if($this->hasProperty($name, 'value')) {
            $result =  $this->_data[$name]->value;
        }

        return $result;
    }

    /**
     * Set the a state value
     *
     * This function only acts on existing states, if a state has changed it will call back to the model triggering a
     * reset action.
     *
     * This function only accepts scalar or array values. Values are only filtered if not NULL. If the value is an empty
     * string it will be filtered to NULL. Values will only be set if the state exists. Function will not create new
     * states. Use the insert() function instead.
     *
     * @param   string        $name
     * @param   scalar|array  $value
     * @throws \UnexpectedValueException If the value is not a scalar or an array
     * @return  ModelState
     */
    public function set($name, $value = null)
    {
        if ($this->has($name) && $this->get($name) !== $value)
        {
            $this->_data[$name]->value = $value;
            $this->_sanitize($this->_data[$name]);

            //Reset the mode
            $this->__model->reset(array($name));

        }
        else $this->__defaults[$name] = $value;

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
        return array_key_exists($name, $this->_data);
    }

    /**
     * Remove an existing state
     *
     * @param   string $name The name of the state
     * @return  ModelState
     */
    public function remove( $name )
    {
        unset($this->_data[$name]);
        return $this;
    }

    /**
     * Reset all state data and revert to the default state
     *
     * @param   boolean $default If TRUE use defaults when resetting. Default is TRUE
     * @return ModelState
     */
    public function reset($default = true)
    {
        foreach($this->_data as $state) {
            $this->set($state->name, $default ? $state->default : null);
        }

        return $this;
    }

    /**
     * Get the total number of entities
     *
     * @return  int
     */
    public function count()
    {
        //Reset the model
        return $this->__model->count();
    }

    /**
     * Set the state values from an array
     *
     * @param   array $data An associative array of state values by name
     * @return  ModelState
     */
    public function setValues(array $data)
    {
        foreach($data as $key => $value) {
           $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Get the state data
     *
     * This function only returns states that have been been set.
     *
     * @param   boolean $unique If TRUE only retrieve unique state values, default FALSE
     * @return  array   An associative array of state values by name
     */
    public function getValues($unique = false)
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
     * Set a state property
     *
     * @param string $name      The name of the state
     * @param string $property  The name of the property
     * @param mixed  $value     The value of the property
     * @return ModelState
     */
    public function setProperty($name, $property, $value)
    {
        if($this->has($name))
        {
            if($this->hasProperty($name, $property))
            {
                if($property !== 'value') {
                    $this->_data[$name]->$property = $value;
                } else {
                    $this->set($name, $value);
                }
            }
        }

        return $this;
    }

    /**
     * Get a state property
     *
     * @param string $name     The name of the state
     * @param string $property The name of the property
     * @return mixed|null   Return the property value or NULL if the property does not exist
     */
    public function getProperty($name, $property)
    {
        $result = null;
        if($this->has($name))
        {
            if(isset($this->_data[$name]->$property)) {
                $result = $this->_data[$name]->$property;
            }
        }

        return $result;
    }

    /**
     * Check if a state property exists
     *
     * @param string $name     The name of the state
     * @param string $property The name of the property
     * @return boolean   Return TRUE if the the property exists, FALSE otherwise
     */
    public function hasProperty($name, $property)
    {
        $result = false;
        if($this->has($name)) {
            $result = property_exists($this->_data[$name], $property);
        }

        return $result;
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
        $states = $this->getValues(true);

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
     * @param   array   $exclude An array of states names to exclude.
     * @return  boolean TRUE if the state is empty, otherwise FALSE.
     */
    public function isEmpty(array $exclude = array())
    {
        $states = $this->getValues();

        foreach($exclude as $state) {
            unset($states[$state]);
        }

        return (bool) (count($states) == 0);
    }

    /**
     * Get an state value
     *
     * @param   string $name
     * @return  mixed  The state value
     */
    final public function offsetGet($name)
    {
        return $this->get($name);
    }

    /**
     * Set an state value
     *
     * @param   string        $name
     * @param   scalar|array  $value
     * @return  void
     */
    final public function offsetSet($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Check if the offset exists
     *
     * Required by interface \ArrayAccess
     *
     * @param   int   $offset
     * @return  bool
     */
    final public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Unset an item in the array
     *
     * All numerical array keys will be modified to start counting from zero while literal keys won't be touched.
     *
     * Required by interface \ArrayAccess
     *
     * @param   int     $offset
     * @return  void
     */
    final public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

	/**
     * Validate a unique state.
     *
     * @param  object  $state The state object.
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

    /**
     * Sanitize a state by filtering the state value
     *
     * @param  object  $state The state object.
     * @throws \UnexpectedValueException If the value is not a scalar or an array
     * @return object
     */
    protected function _sanitize($state)
    {
        //Only filter if we have a value
        if(!empty($state->value))
        {
            //Only accepts scalar values and array
            if(!is_scalar($state->value) && !is_array($state->value))
            {
                throw new \UnexpectedValueException(
                    'Value needs to be an array or a scalar, "'.gettype($state->value).'" given'
                );
            }

            $filter = $state->filter;

            if(!($filter instanceof FilterInterface)) {
                $filter =  $this->getObject('filter.factory')->createChain($filter);
            }

            $state->value = $filter->sanitize($state->value);
        }

        return $state;
    }
}