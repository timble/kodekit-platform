<?php
/**
 * @package        Koowa_Database
 * @subpackage     Behavior
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Abstract Database Behavior
 *
 * @author        Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage     Behavior
 */
abstract class DatabaseBehaviorAbstract extends BehaviorAbstract implements ServiceInstantiatable
{
    /**
     * Instantiate the object
     *
     * If the behavior is auto mixed also lazy mix it into related row objects.
     *
     * @param 	Config                 $config	  A Config object with configuration options
     * @param 	ServiceManagerInterface	$manager  A ServiceInterface object
     * @return  object
     */
    public static function getInstance(Config $config, ServiceManagerInterface $manager)
    {
        $classname = $config->service_identifier->classname;
        $instance  = new $classname($config);

        //If the behavior is auto mixed also lazy mix it into related row objects.
        if ($config->auto_mixin)
        {
            $identifier = clone $instance->getMixer()->getIdentifier();
            $identifier->path = array('database', 'row');
            $identifier->name = Inflector::singularize($identifier->name);

            $manager->addMixin($identifier, $instance);
        }

        return $instance;
    }

    /**
     * Command handler
     *
     * This function translates the command name to a command handler function of the format '_before[Command]' or
     * '_after[Command]. Command handler functions should be declared protected.
     *
     * @param     string    The command name
     * @param     object    The command context
     * @return    boolean   Can return both true or false.
     */
    public function execute($name, CommandContext $context)
    {
        if ($context->data instanceof DatabaseRowInterface) {
            $this->setMixer($context->data);
        }

        return parent::execute($name, $context);
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

        return $this->_mixer;
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

        return $this->_mixer;
    }

    /**
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a function of format is[Behavior] to allow client code to check if the
     * behavior is callable.
     *
     * @param object The mixer requesting the mixable methods.
     * @return array An array of methods
     */
    public function getMixableMethods(Object $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);

        unset($methods['save']);
        unset($methods['delete']);
        unset($methods['getInstance']);

        return $methods;
    }
}