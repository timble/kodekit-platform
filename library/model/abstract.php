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
 * Abstract Model
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
abstract class ModelAbstract extends Object implements ModelInterface
{
    /**
     * A state object
     *
     * @var ModelStateInterface
     */
    private $__state;

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
     * @param  ObjectConfig $config    An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        // Set the state identifier
        $this->__state = $config->state;
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
            'state' => 'lib:model.state',
        ));

        parent::_initialize($config);
    }

    /**
     * Reset the model data and state
     *
     * @param  boolean $default If TRUE use defaults when resetting the state. Default is TRUE
     * @return ModelAbstract
     */
    public function reset($default = true)
    {
        $this->_rowset = null;
        $this->_row    = null;
        $this->_total  = null;

        $this->getState()->reset($default);

        return $this;
    }

    /**
     * Set the model state values
     *
     * @param  array $values Set the state values
     * @return ModelAbstract
     */
    public function setState(array $values)
    {
        $this->getState()->setValues($values);
        return $this;
    }

    /**
     * Get the model state object
     *
     * @return  ModelStateInterface  The model state object
     */
    public function getState()
    {
        if(!$this->__state instanceof ModelStateInterface)
        {
            $this->__state = $this->getObject($this->__state, array('model' => $this));

            if(!$this->__state instanceof ModelStateInterface)
            {
                throw new \UnexpectedValueException(
                    'State: '.get_class($this->__state).' does not implement ModelStateInterface'
                );
            }
        }

        return $this->__state;
    }

    /**
     * State Change notifier
     *
     * This function is called when the state has changed.
     *
     * @param  string 	$name  The state name being changed
     * @return void
     */
    public function onStateChange($name)
    {
        $this->_rowset = null;
        $this->_row    = null;
        $this->_total  = null;
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
        if ($this->getState()->isUnique()) {
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
            'offset' => (int) $this->getState()->offset,
            'limit'  => (int) $this->getState()->limit,
            'total'  => (int) $this->getTotal(),
        ));

        return $paginator;
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
            $this->getState()->set($method, $args[0]);
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

        $this->__state = clone $this->__state;
    }
}