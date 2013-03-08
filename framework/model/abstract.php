<?php
/**
 * @package     Koowa_Model
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract Model Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Model
 */
abstract class ModelAbstract extends Object implements ModelInterface
{
    /**
     * A state object
     *
     * @var ModelStateInterface
     */
    protected $_state;

    /**
     * List total
     *
     * @var integer
     */
    protected $_total;

    /**
     * Model list data
     *
     * @var DatabaseRowsetInterface
     */
    protected $_rowset;

    /**
     * Model row data
     *
     * @var DatabaseRowInterface
     */
    protected $_row;

    /**
     * Constructor
     *
     * @param  Config $config    An optional Config object with configuration options
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        //Set the model state
        $this->setState($config->state);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Config $config An optional Config object with configuration options
     * @return  void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'state' => new ModelState(),
        ));

        parent::_initialize($config);
    }

    /**
     * Set the model state values
     *
     * This function only acts on existing states it will reset (unsets) the $_rowset, $_item and $_total model
     * properties when a state changes.
     *
     * @param   string|array|object  $name  The name of the property, an associative array or an object
     * @param   mixed                $value The value of the property
     * @return  ModelAbstract
     */
    public function set($name, $value = null)
    {
        $changed = false;

        if (is_object($name)) {
            $name = (array)Config::unbox($name);
        }

        if (is_array($name))
        {
            foreach ($name as $key => $value)
            {
                if ($this->_state->has($key) && $this->_state->get($key) != $value)
                {
                    $changed = true;
                    break;
                }
            }

            $this->_state->fromArray($name);
        }
        else
        {
            if ($this->getState()->has($name) && $this->_state->get($name) != $value) {
                $changed = true;
            }

            $this->_state->set($name, $value);
        }

        if ($changed)
        {
            $this->_rowset = null;
            $this->_row = null;
            $this->_total = null;
        }

        return $this;
    }

    /**
     * Get the model state value
     *
     * If no state name is given then the function will return an associative array of all state values
     *
     * If the property does not exist and a  default value is specified this is returned, otherwise the function return
     * NULL.
     *
     * @param   string  $name   The name of the property
     * @param   mixed   $default The default value
     * @return  mixed   The value of the property, an associative array or NULL
     */
    public function get($name = null, $default = null)
    {
        $result = $default;

        if (is_null($name)) {
            $result = $this->_state->toArray();
        }
        else
        {
            if ($this->_state->has($name)) {
                $result = $this->_state->get($name);
            }
        }

        return $result;
    }

    /**
     * Reset all cached data and reset the model state to it's default
     *
     * @param  boolean $default If TRUE use defaults when resetting. Default is TRUE
     * @return ModelAbstract
     */
    public function reset($default = true)
    {
        $this->_rowset = null;
        $this->_row = null;
        $this->_total = null;

        $this->_state->reset($default);

        return $this;
    }

    /**
     * Set the model state object
     *
     * @param  ModelState $state A model state object
     * @return ModelAbstract
     */
    public function setState(ModelState $state)
    {
        $this->_state = $state;
        return $this;
    }

    /**
     * Get the model state object
     *
     * @return  ModelState  The model state object
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * Method to get a item
     *
     * @return  object
     */
    public function getRow()
    {
        return $this->_row;
    }

    /**
     * Get a list of items
     *
     * @return  object
     */
    public function getRowset()
    {
        return $this->_rowset;
    }

    /**
     * Get the total amount of items
     *
     * @return  int
     */
    public function getTotal()
    {
        return $this->_total;
    }

    /**
     * Get the model data
     *
     * If the model state is unique this function will call getRow(), otherwise it will call getRowset().
     *
     * @return DatabaseRowsetInterface or DatabaseRowInterface
     */
    public function getData()
    {
        if ($this->_state->isUnique()) {
            $data = $this->getRow();
        } else {
            $data = $this->getRowset();
        }

        return $data;
    }

    /**
     * Get the model paginator object
     *
     * @return  ModelPaginator  The model paginator object
     */
    public function getPaginator()
    {
        $paginator = new ModelPaginator(array(
            'offset' => (int) $this->offset,
            'limit'  => (int) $this->limit,
            'total'  => (int) $this->getTotal(),
        ));

        return $paginator;
    }

    /**
     * Get a model state by name
     *
     * @param   string  $key The key name.
     * @return  string  The corresponding value.
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Set a model state by name
     *
     * @param   string  $key   The key name.
     * @param   mixed   $value The value for the key
     * @return  void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Supports a simple form Fluent Interfaces. Allows you to set states by using the state name as the method name.
     *
     * For example : $model->sort('name')->limit(10)->getRowset();
     *
     * @param   string  $method Method name
     * @param   array   $args   Array containing all the arguments for the original call
     * @return  ModelAbstract
     *
     * @see http://martinfowler.com/bliki/FluentInterface.html
     */
    public function __call($method, $args)
    {
        if ($this->getState()->has($method))
        {
            $this->set($method, $args[0]);
            return $this;
        }

        return parent::__call($method, $args);
    }

    /**
     * Preform a deep clone of the object.
     *
     * @retun void
     */
    public function __clone()
    {
        parent::__clone();

        $this->_state = clone $this->_state;
    }
}