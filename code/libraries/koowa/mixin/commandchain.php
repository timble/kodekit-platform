<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Chain of command mixin
 * 
 * Class can be used as a mixin in classes that want to implement a chain 
 * of responsability or chain of command pattern.
 *  
 * @author      Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Mixin
 * @uses 		KCommandChain
 * @uses 		KCommandInterface
 * @uses		KCommandEvent
 */
class KMixinCommandchain extends KMixinAbstract
{   
    /**
     * Chain of command object
     *
     * @var	KCommandChain
     */
    protected $_command_chain;
	
	/**
	 * Object constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
			
		//Create a command chain object 
		$this->_command_chain = $config->command_chain;

		//Enqueue the event command with a low priority to make sure that all other
		//commands and ran first
		if($config->auto_events) {
			$this->_command_chain->enqueue(new KCommandEvent(), KCommandChain::PRIORITY_LOWEST);
		}
	}
	
	/**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
            'command_chain'   =>  new KCommandChain(),
    		'auto_events'     => true
        ));
        
        parent::_initialize($config);
    }
    
	/**
	 * Get the command chain context
	 * 
     * This functions inserts a 'caller' variable in the context which contains
     * the mixer object.
	 *
	 * @return 	KCommandContext
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
	 * @return 	KCommandChain
	 */
	public function getCommandChain()
	{
		return $this->_command_chain;
	}
	
	/**
	 * Set the chain of command object
	 *
	 * @var 	KCommandInterface
	 * @return  KObject 	The mixer object
	 */
	public function setCommandChain(KCommandInterface $command_chain)
	{
		$this->_command_chain = $command_chain;
		return $this->_mixer;
	}
}