<?php
/**
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Command Mixin
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
class KMixinCommand extends KMixinAbstract
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
        
        if(is_null($config->command_chain)) {
			throw new \InvalidArgumentException('command_chain [KCommandChainInterface] config option is required');
		}
            
        //Create a command chain object 
        $this->_command_chain = $config->command_chain;
        
        //Set the mixer in the config
        $config->mixer = $this->_mixer;
        
        //Mixin the callback mixer if callbacks have been enabled
        if($config->enable_callbacks)
        {
            $callback = new KMixinCallback($config);

            //Mixin the callback mixin
            $this->_mixer->mixin($callback);

            //Enqueue the command in the mixer's command chain
            $this->getCommandChain()->enqueue($callback, $config->callback_priority);
        }
        
        //Enqueue the event command with a lowest priority to make sure it runs last
        if($config->dispatch_events) 
        { 
            $this->_mixer->mixin(new KMixinEvent($config));

            $command = $this->_command_chain->getService('lib://nooku/command.event', array(
            	'event_dispatcher' => $this->getEventDispatcher()
            ));

            $this->getCommandChain()->enqueue($command, $config->event_priority);
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
            'command_chain'     => null,
            'event_dispatcher'  => null,
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
     * This functions sets the command subject as the mixer in the context
     *
     * @return  KCommandContext
     */
    public function getCommandContext()
    {
        $context = $this->_command_chain->getContext();
        $context->setSubject($this->_mixer);
        
        return $context;
    }
    
    /**
     * Get the chain of command object
     *
     * @return  KCommandChain
     */
    public function getCommandChain()
    {
        if(!$this->_command_chain instanceof KCommandChainInterface)
        {
            $this->_command_chain = $this->getService($this->_command_chain);

            //Make sure the request implements KControllerRequestInterface
            if(!$this->_command_chain instanceof KCommandChainInterface)
            {
                throw new \UnexpectedValueException(
                    'CommandChain: '.get_class($this->_command_chain).' does not implement KCommandChainInterface'
                );
            }
        }

        return $this->_command_chain;
    }
    
    /**
     * Set the chain of command object
     *
     * @param   object 	A command chain object
     * @return  KObject The mixer object
     */
    public function setCommandChain(KCommandChainInterface $chain)
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