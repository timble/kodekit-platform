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
 * Command Mixin
 * 
 * Class can be used as a mixin in classes that want to implement a chain of responsibility or chain of command pattern.
 *  
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
 */
class CommandMixin extends ObjectMixinAbstract
{   
    /**
     * Chain of command object
     *
     * @var CommandChain
     */
    protected $_command_chain;
    
    /**
     * Object constructor
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);
        
        if(is_null($config->command_chain)) {
			throw new \InvalidArgumentException('command_chain [CommandChainInterface] config option is required');
		}
            
        //Create a command chain object
        $this->_command_chain = $config->command_chain;

        //Enqueue the callback command
        if($config->enable_callbacks)
        {
            $command = $this->getMixer()->mixin('lib:command.callback', $config);
            $this->getCommandChain()->enqueue($command, $config->callback_priority);
        }
        
        //Enqueue the event command
        if($config->dispatch_events) 
        {
            $command = $this->getMixer()->mixin('lib:command.event', $config);
            $this->getCommandChain()->enqueue($command, $config->event_priority);
        }
    }
    
    /**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'command_chain'     => null,
            'event_dispatcher'  => null,
            'dispatch_events'   => true,
            'event_priority'    => Command::PRIORITY_LOWEST,
            'enable_callbacks'  => false,
            'callback_priority' => Command::PRIORITY_HIGH,
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Get the command chain context
     * 
     * This functions sets the command subject as the mixer in the context
     *
     * @return  CommandContext
     */
    public function getCommandContext()
    {
        $context = $this->_command_chain->getContext();
        $context->setSubject($this->getMixer());
        
        return $context;
    }
    
    /**
     * Get the chain of command object
     *
     * @return  CommandChain
     */
    public function getCommandChain()
    {
        if(!$this->_command_chain instanceof CommandChainInterface)
        {
            $this->_command_chain = $this->getObject($this->_command_chain);

            if(!$this->_command_chain instanceof CommandChainInterface)
            {
                throw new \UnexpectedValueException(
                    'CommandChain: '.get_class($this->_command_chain).' does not implement CommandChainInterface'
                );
            }
        }

        return $this->_command_chain;
    }
    
    /**
     * Set the chain of command object
     *
     * @param   CommandChainInterface 	$chain A command chain object
     * @return  Object The mixer object
     */
    public function setCommandChain(CommandChainInterface $chain)
    {
        $this->_command_chain = $chain;
        return $this->getMixer();
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