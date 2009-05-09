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
	
	public function __construct($object, KPatternCommandInterface $command_chain = null)
	{
		parent::__construct($object);
		
		//Create a command chain object if we didn't get one passed in
		$this->_command_chain = is_null($command_chain) ? new KPatternCommandChain : $command_chain;

		//Enqueue the event command 
		$this->_command_chain->enqueue(new KCommandEvent());
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