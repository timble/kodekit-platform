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
 * Abstract Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
abstract class DatabaseBehaviorAbstract extends BehaviorAbstract implements ObjectInstantiable
{
    /**
     * Instantiate the object
     *
     * If the behavior is auto mixed also lazy mix it into related row objects.
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  DatabaseBehaviorAbstract
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        $instance  = new static($config);

        //Lazy mix behavior into related row objects. A supported behavior always has one is[Behaviorable] method.
        if ($instance->isSupported() && $instance->getMixer() && count($instance->getMixableMethods()) > 1)
        {
            $identifier = $instance->getMixer()->getIdentifier()->toArray();
            $identifier['path'] = array('database', 'row');
            $identifier['name'] = StringInflector::singularize($identifier['name']);

            $manager->registerMixin($identifier, $instance);
        }

        return $instance;
    }

    /**
     * Execute the handler
     *
     * @param CommandInterface         $command    The command
     * @param CommandChainInterface    $chain      The chain executing the command
     * @return mixed|null If a handler breaks, returns the break condition. NULL otherwise.
     */
    public function execute(CommandInterface $command, CommandChainInterface $chain)
    {
        if ($command->data instanceof DatabaseRowInterface) {
            $this->setMixer($command->data);
        }

        return parent::execute($command, $chain);
    }

    /**
     * Saves the row or rowset in the database.
     *
     * This function specialises the DatabaseRow or DatabaseRowset save function and auto-disables the tables
     * command chain to prevent recursive looping.
     *
     * @return DatabaseRowAbstract or DatabaseRowsetAbstract
     * @see DatabaseRow::save or DatabaseRowset::save
     */
    public function save()
    {
        //Clone the mixer to prevent status changes
        $mixer = clone $this->getMixer();

        $mixer->getTable()->getCommandChain()->setEnabled(false);
        $mixer->save();
        $mixer->getTable()->getCommandChain()->setEnabled(true);

        return $this->getMixer();
    }

    /**
     * Deletes the row form the database.
     *
     * This function specialises the DatabaseRow or DatabaseRowset delete function and auto-disables the tables
     * command chain to prevent recursive looping.
     *
     * @return DatabaseRowAbstract
     */
    public function delete()
    {
        //Clone the mixer to prevent status changes
        $mixer = clone $this->getMixer();

        $mixer->getTable()->getCommandChain()->setEnabled(false);
        $mixer->delete();
        $mixer->getTable()->getCommandChain()->setEnabled(true);

        return $this->getMixer();
    }

    /**
     * Get the methods that are available for mixin based
     *
     * Methods will only be mixed if the behavior is supported. Otherwise only an is[Behavior] method will be mixed
     * which returns false.
     *
     * @param  array $exclude     An array of public methods to be exclude
     * @return array An array of methods
     */
    public function getMixableMethods($exclude = array())
    {
        $exclude = array_merge($exclude, array('getInstance', 'save', 'delete'));
        return parent::getMixableMethods($exclude);
    }
}