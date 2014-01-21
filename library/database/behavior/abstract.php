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
 * Abstract Database Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
abstract class DatabaseBehaviorAbstract extends BehaviorDynamic implements ObjectInstantiable
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
        $class     = $manager->getClass($config->object_identifier);
        $instance  = new $class($config);

        //If the behavior is auto mixed also lazy mix it into related row objects.
        if ($config->auto_mixin)
        {
            $identifier = $instance->getMixer()->getIdentifier()->toArray();
            $identifier['path'] = array('database', 'row');
            $identifier['name'] = StringInflector::singularize($identifier['name']);

            $manager->registerMixin($identifier, $instance);
        }

        return $instance;
    }

    /**
     * Command handler
     *
     * @param  CommandInterface $command    The command
     * @param  mixed            $condition  The break condition
     * @return array|mixed Returns an array of the callback results in FIFO order. If a handler breaks and the break
     *                     condition is not NULL returns the break condition.
     */
    public function executeCommand(CommandInterface $command, $condition = null)
    {
        if ($command->data instanceof DatabaseRowInterface) {
            $this->setMixer($command->data);
        }

        return parent::executeCommand($command, $condition);
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

        $mixer->getTable()->getCommandChain()->disable();
        $mixer->save();
        $mixer->getTable()->getCommandChain()->enable();

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

        $mixer->getTable()->getCommandChain()->disable();
        $mixer->delete();
        $mixer->getTable()->getCommandChain()->enable();

        return $this->getMixer();
    }

    /**
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a function of format is[Behavior] to allow client code to check if the
     * behavior is callable.
     *
     * @param ObjectInterface $mixer The mixer requesting the mixable methods.
     * @return array An array of methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);

        unset($methods['save']);
        unset($methods['delete']);
        unset($methods['getInstance']);

        return $methods;
    }
}