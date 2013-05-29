<?php
/**
 * @package     Koowa_Model
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

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
     * @var DatabaseRow(set)Interface
     */
    protected $_data;

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

        // Mixin the command interface
        $this->mixin('lib:command.mixin', $config);

        // Mixin the behavior interface
        $this->mixin('lib:behavior.mixin', $config);
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
            'state'             => 'lib:model.state',
            'command_chain'     => 'lib:command.chain',
            'dispatch_events'   => false,
            'event_dispatcher'  => null,
            'enable_callbacks'  => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Fetch a row or a rowset based on the model state
     *
     * @param integer  $mode The database fetch style.
     * @return DatabaseRow(set)Interface
     */
    public function fetch($mode = Database::FETCH_ROWSET)
    {
        if(!isset($this->_data))
        {
            //Create commandchain context
            $context = $this->getCommandContext();
            $context->mode = $mode;
            $context->data = null;

            if ($this->getCommandChain()->run('before.fetch', $context) !== false)
            {
                if($mode == Database::FETCH_ROW) {
                    $context->data = $this->getRow();
                } else {
                    $context->data = $this->getRowset();
                }

                $this->getCommandChain()->run('after.fetch', $context);
            }

            $this->_data = ObjectConfig::unbox($context->data);
        }

        return $this->_data;
    }

    /**
     * Reset the model data and state
     *
     * @param  boolean $default If TRUE use defaults when resetting the state. Default is TRUE
     * @return ModelAbstract
     */
    public function reset($default = true)
    {
        $this->_data  = null;
        $this->_total = null;

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
        $this->_data  = null;
        $this->_total = null;
    }

    /**
     * Method to get a item
     *
     * @return  object
     */
    public function getRow()
    {
        return $this->_data;
    }

    /**
     * Get a list of items
     *
     * @return  object
     */
    public function getRowset()
    {
        return $this->_data;
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
     * For example : $model->sort('name')->limit(10)->fetch();
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

    /**
     * Fetch the data when model is invoked.
     *
     * @param integer  $mode The database fetch style.
     * @return DatabaseRow(set)Interface
     */
    public function __invoke($mode = Database::FETCH_ROWSET)
    {
        return $this->fetch($mode);
    }
}