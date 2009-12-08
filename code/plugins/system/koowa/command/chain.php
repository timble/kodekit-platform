<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Command
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Command Chain
 * 
 * The command queue implements a double linked list. The command handle is used 
 * as the key. Each command can have a priority, default priority is 3 The queue 
 * is ordered by priority, command with a higher priority are called first.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Command
 */
class KCommandChain extends KObject
{
	/**
	 * Command list
	 *
	 * @var array
	 */
	protected $_command = null;
	
	
	/**
	 * Priority list
	 *
	 * @var array
	 */
	protected $_priority = null;

	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->_command  = new ArrayObject();
		$this->_priority = new ArrayObject();
	}
	
  	/**
	 * Attach a command to the chain
	 * 
	 * @param 	object 		A KCommandHandler 
	 * @param 	integer		The command priority, usually between 1 (high priority) and 5 (low), default is 3
	 * @return	 KCommandChain
	 */
	public function enqueue( KCommandInterface $cmd, $priority = 3)
	{
		$handle = $cmd->getHandle(); //get the object handle
		
		$this->_command->offsetSet($handle, $cmd);
		
		$this->_priority->offsetSet($handle, $priority);
		$this->_priority->asort(); //sort the entries by priority
		return $this;
  	}
  	
	/**
	 * Remove a command from the chain
	 * 
	 * @param 	object 		A KCommandHandler 
	 * @param 	integer		The command priority
	 * @return 	KCommandChain
	 */
	public function dequeue( KCommandInterface $cmd)
	{
		$handle = $cmd->getHandle(); //get the object handle
		
  		if($this->_command->offsetExist($handle)) {
			$this->_command->offsetUnset($handle);	
		}

		return $this;
  	}
  	
  	/**
	 * Run the commands in the chain
	 * 
	 * If a command return false the executing is halted
	 * 
	 * @param 	string  The command name
	 * @param 	mixed   The command context
	 * @return	boolean True if successfull, otherwise false
	 */
  	public function run( $name, KCommandContext $context )
  	{
  		$iterator = $this->_priority->getIterator();

		while($iterator->valid()) 
		{
    		$cmd = $this->_command[ $iterator->key()];
    		
			if ( $cmd->execute( $name, $context ) === false) {
      			return false;
      		}

    		$iterator->next();
		}
		
		return true;
  	}
  	
  	/**
	 * Set the priority of a command
	 * 
	 * @param object 	A KCommandHandler 
	 * @param integer	The command priority
	 * @return KCommandChain
	 */
  	public function setPriority(KCommandInterface $cmd, $priority)
  	{
  		$handle = $cmd->getHandle();
		
		if($this->_priority->offsetExists($handle)) {
			$this->_priority->offsetSet($handle, $priority);
		}
		
		return $this;
  	}
  	
  	/**
	 * Get the priority of a command
	 * 
	 * @param object 	A KCommandHandler 
	 * @param integer	The command priority
	 * @return	integer The command priority
	 */
  	public function getPriority(KCommandInterface $cmd)
  	{
  		$handle = $cmd->getHandle();
  	
  		$result = null;
  		if($this->_priority->offsetExist($handle)) {
			$result = $this->_priority->offsetGet($handle);
		}
		
		return $result;
  	}
}