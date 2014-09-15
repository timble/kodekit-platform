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
 * Abstract Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Model
 */
abstract class ModelAbstract extends Object implements ModelInterface, CommandCallbackDelegate
{
    /**
     * A state object
     *
     * @var ModelStateInterface
     */
    private $__state;

    /**
     * Entity count
     *
     * @var integer
     */
    protected $_count;

    /**
     * Entity object
     *
     * @var ModelEntityInterface
     */
    protected $_entity;

    /**
     * Name of the identity key
     *
     * @var    string
     */
    protected $_identity_key;

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

        // Set the identity key
        $this->_identity_key = $config->identity_key;

        // Mixin the behavior interface
        $this->mixin('lib:behavior.mixin', $config);

        // Mixin the event interface
        $this->mixin('lib:event.mixin', $config);
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
            'identity_key'     => null,
            'state'            => 'lib:model.state',
            'command_chain'    => 'lib:command.chain',
            'command_handlers' => array('lib:command.handler.event'),
        ));

        parent::_initialize($config);
    }

    /**
     * Fetch an entity from the data store
     *
     * @return ModelEntityInterface
     */
    final public function fetch()
    {
        if(!isset($this->_entity))
        {
            $context = $this->getContext();
            $context->entity  = null;

            if ($this->invokeCommand('before.fetch', $context) !== false)
            {
                $context->entity = $this->_actionFetch($context);
                $this->invokeCommand('after.fetch', $context);
            }

            $this->_entity = ObjectConfig::unbox($context->entity);
        }

        return $this->_entity;
    }

    /**
     * Create a new entity for the data source
     *
     * @param  array $properties Array of entity properties
     * @return  ModelEntityInterface
     */
    final public function create(array $properties = array())
    {
        $context = $this->getContext();
        $context->entity = $properties;

        if ($this->invokeCommand('before.create', $context) !== false)
        {
            $context->entity = $this->_actionCreate($context);
            $this->invokeCommand('after.create', $context);
        }

        $this->_entity = ObjectConfig::unbox($context->entity);

        return $this->_entity;
    }

    /**
     * Get the total number of entities
     *
     * @return  int
     */
    final public function count()
    {
        if(!isset($this->_count))
        {
            $context = $this->getContext();
            $context->count = null;

            if ($this->invokeCommand('before.count', $context) !== false)
            {
                $context->count = $this->_actionCount($context);
                $this->invokeCommand('after.count', $context);
            }

            $this->_count = ObjectConfig::unbox($context->count);
        }

        return $this->_count;
    }

    /**
     * Reset the model data and state
     *
     * @param  array $modified List of changed state names
     * @return $this
     */
    final public function reset(array $modified = array())
    {
        $context = $this->getContext();
        $context->modified = $modified;

        if ($this->invokeCommand('before.reset', $context) !== false)
        {
            $this->_actionReset($context);
            $this->invokeCommand('after.reset', $context);
        }

        return $this;
    }

    /**
     * Invoke a command handler
     *
     * @param string            $method   The name of the method to be executed
     * @param CommandInterface  $command   The command
     * @return mixed Return the result of the handler.
     */
    public function invokeCommandCallback($method, CommandInterface $command)
    {
        return $this->$method($command);
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
     * Get the model context
     *
     * @return  ModelContext
     */
    public function getContext()
    {
        $context = new ModelContext();
        $context->setSubject($this);
        $context->setState($this->getState());
        $context->setIdentityKey($this->_identity_key);

        return $context;
    }

    /**
     * Create a new entity for the data source
     *
     * @param ModelContext $context A model context object
     * @return  ModelEntityInterface The entity
     */
    protected function _actionCreate(ModelContext $context)
    {
        //Get the data
        $data = ModelContext::unbox($context->entity);

        //Create the entity identifier
        $identifier = $this->getIdentifier()->toArray();
        $identifier['path'] = array('model', 'entity');

        if(!is_numeric(key($data))) {
            $identifier['name'] = StringInflector::singularize($identifier['name']);
        } else {
            $identifier['name'] = StringInflector::pluralize($identifier['name']);
        }

        $options = array(
            'data'         => $data,
            'identity_key' => $context->getIdentityKey()
        );

        return $this->getObject($identifier, $options);
    }

    /**
     * Fetch a new entity from the data source
     *
     * @param ModelContext $context A model context object
     * @return ModelEntityInterface The entity
     */
    protected function _actionFetch(ModelContext $context)
    {
        $identifier = $this->getIdentifier()->toArray();
        $identifier['path'] = array('model', 'entity');
        $identifier['name'] = StringInflector::pluralize($identifier['name']);

        $options = array(
            'identity_key' => $context->getIdentityKey()
        );

        return $this->getObject($identifier, $options);
    }

    /**
     * Get the total number of entities
     *
     * @param ModelContext $context A model context object
     * @return integer  The total number of entities
     */
    protected function _actionCount(ModelContext $context)
    {
        return count($this->fetch());
    }

    /**
     * Reset the model
     *
     * @param  string $name The state name being changed
     *
     * @return void
     */
    protected function _actionReset(ModelContext $context)
    {
        $this->_entity = null;
        $this->_count  = null;
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
     * @return ModelEntityInterface
     */
    public function __invoke()
    {
        return $this->fetch();
    }
}