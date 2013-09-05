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
 * Model State Class
 *
 * A state requires a model object. It will call back to the model upon a state change using the onStateChange notifier.
 *
 * State values can only be of type scalar or array. Values are only filtered if not NULL. If the value is an empty
 * string it will be filtered to NULL. Values will only be set if the state exists. To insert new states use the
 * the insert() function.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
class ModelState extends ObjectArray implements ModelStateInterface
{
    /**
     * Model object
     *
     * @var string|object
     */
    protected $_model;

    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return ObjectArray
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

        $this->_model = $config->model;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'model' => null,
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
     * @return  ModelState
     */
    public function insert($name, $filter, $default = null, $unique = false, $required = array())
    {
        //Create the state
        $state = new \stdClass();
        $state->name     = $name;
        $state->filter   = $filter;
        $state->value    = null;
        $state->unique   = $unique;
        $state->required = $required;
        $state->default  = $default;
        $this->_data[$name] = $state;

        //Set the value to default
        if(isset($default)) {
            $this->offsetSet($name, $default);
        }

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
        if($this->offsetExists($name)) {
            $result = $this->offsetGet($name);
        }

        return $result;
    }

    /**
     * Set the a state value
     *
     * This function only acts on existing states, if a state has changed it will call back to the model triggering the
     * onStateChange notifier.
     *
     * @param  	string 	$name  The state name.
     * @param  	mixed  	$value The state value.
     * @return  ModelAbstract
     */
    public function set($name, $value = null)
    {
        if ($this->has($name) && $this->get($name) != $value)
        {
            $this->offsetSet($name, $value);

            //Notify the model
            $this->_model->onStateChange($name);
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
        return $this->offsetExists($name);
    }

    /**
     * Remove an existing state
     *
     * @param   string $name The name of the state
     * @return  ModelState
     */
    public function remove( $name )
    {
        $this->offsetUnset($name);
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
        foreach($this->_data as $state)
        {
            $state->value = $default ? $state->default : null;

            //Notify the model
            $this->_model->onStateChange($state->name);
        }

        return $this;
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
    public function offsetGet($name)
    {
        $result = null;

        if (isset($this->_data[$name])) {
            $result = $this->_data[$name]->value;
        }

        return $result;
    }

    /**
     * Set an state value
     *
     * This function only accepts scalar or array values. Values are only filtered if not NULL. If the value is an empty
     * string it will be filtered to NULL. Values will obly be set if the state exists. Function will not create new
     * states. Use the insert() function instead.
     *
     * @param   string        $name
     * @param   scalar|array  $value
     * @throws \UnexpectedValueException If the value is not a scalar or an array
     * @return  void
     */
    public function offsetSet($name, $value)
    {
        if($this->offsetExists($name))
        {
            //Only filter if we have a value
            if($value !== null)
            {
                if($value !== '')
                {
                    //Only accepts scalar values
                    if(!is_scalar($value) && !is_array($value))
                    {
                        throw new \UnexpectedValueException(
                            'Value needs to be an array or a scalar, "'.gettype($value).'" given'
                        );
                    }

                    $filter = $this->_data[$name]->filter;

                    if(!($filter instanceof FilterInterface)) {
                        $filter =  $this->getObject('lib:filter.factory')->getFilter($filter);
                    }

                    $value = $filter->sanitize($value);
                }
                else $value = null;

                $this->_data[$name]->value = $value;
            }
        }
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
}