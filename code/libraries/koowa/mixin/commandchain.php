<?php
/**
 * @version     $Id$
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Chain of command mixin
 *
 * Class can be used as a mixin in classes that want to implement a chain
 * of responsability or chain of command pattern.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Mixin
 * @uses        KCommandChain
 * @uses        KCommandInterface
 * @uses        KCommandEvent
 */
class KMixinCommandchain extends KMixinAbstract
{
    /**
     * Chain of command object
     *
     * @var KCommandChain
     */
    protected $_command_chain;

    /**
     * Object constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Create a command chain object
        $this->_command_chain = $config->command_chain;

        //Mixin the callback mixer if callbacks have been enabled
        if($config->enable_callbacks)
        {
            $this->_mixer->mixin(new KMixinCallback(new KConfig(array(
                'mixer'             => $this->_mixer,
                'command_chain'     => $this->_command_chain,
                'command_priority'  => $config->callback_priority
            ))));
        }

        //Enqueue the event command with a lowest priority to make sure it runs last
        if($config->dispatch_events) {
            $this->_command_chain->enqueue($config->event, $config->event_priority);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'command_chain'     => new KCommandChain(),
            'event'				=> KService::get('koowa:command.event'),
            'dispatch_events'   => true,
            'event_priority'    => KCommand::PRIORITY_LOWEST,
            'enable_callbacks'  => false,
            'callback_priority' => KCommand::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the command chain context
     *
     * This functions inserts a 'caller' variable in the context which contains
     * the mixer object.
     *
     * @return  KCommandContext
     */
    public function getCommandContext()
    {
        $context = $this->_command_chain->getContext();
        $context->caller = $this->_mixer;

        return $context;
    }

    /**
     * Get the chain of command object
     *
     * @return  KCommandChain
     */
    public function getCommandChain()
    {
        return $this->_command_chain;
    }

    /**
     * Set the chain of command object
     *
     * @param   object 	A command chain object
     * @return  KObject The mixer object
     */
    public function setCommandChain(KCommandChain $chain)
    {
        $this->_command_chain = $chain;
        return $this->_mixer;
    }

	/**
     * Preform a deep clone of the object.
     *
     * @retun void
     */
    public function __clone()
    {
        $this->_command_chain = clone $this->_command_chain;
    }
}