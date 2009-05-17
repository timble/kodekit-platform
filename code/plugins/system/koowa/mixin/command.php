<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Chain of command mixin
 * 
 * Class can be used as a mixin in classes that want to implement a chain 
 * of responsability or chain of command pattern.
 *  
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Mixin
 * @uses 		KPatternCommandChain
 * @uses 		KPatternCommandInterface
 * @uses		KCommandEvent
 */
class KMixinCommand extends KMixinAbstract
{   
    /**
     * Chain of command object
     *
     * @var	KPatternCommandInterface
     */
    protected $_command_chain;
	
	/**
	 * Object constructor
	 *
	 * @param	array 	An optional associative array of configuration settings.
	 * Recognized key values include 'mixer', 'command_chain' 
	 * (this list is not meant to be comprehensive).
	 */
	public function __construct(array $options = array())
	{
		// Initialize the options
        $options  = $this->_initialize($options);
		
		parent::__construct($options);
		
		//Create a command chain object 
		$this->_command_chain = $options['command_chain'];

		//Enqueue the event command 
		$this->_command_chain->enqueue(new KCommandEvent());
	}
	
	/**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(array $options)
    {
        $options = parent::_initialize($options);
        
    	$defaults = array(
            'command_chain' =>  new KPatternCommandChain(),
        );

        return array_merge($defaults, $options);
    }
	
	/**
	 * Get the chain of command object
	 *
	 * @return 	KPatternCommandInterface
	 */
	public function getCommandChain()
	{
		return $this->_command_chain;
	}
	
	/**
	 * Set the chain of command object
	 *
	 * @var 	KPatternCommandInterface
	 * @return 	this
	 */
	public function setCommandChain(KPatternCommandInterface $command_chain)
	{
		$this->_command_chain = $command_chain;
		return $this->_mixer;
	}
}