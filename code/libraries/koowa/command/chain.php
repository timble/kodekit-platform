<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Command
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Command Chain
 * 
 * The command queue implements a double linked list. The command handle is used 
 * as the key. Each command can have a priority, default priority is 3 The queue 
 * is ordered by priority, commands with a higher priority are called first.
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
	 * Enabled status of the chain
	 * 
	 * @var boolean
	 */
	protected $_enabled = true;

	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct(KConfig $config = null)
	{
		$this->_command  = new ArrayObject();
		$this->_priority = new ArrayObject();
	}
	
  	/**
	 * Attach a command to the chain
	 * 
	 * @param 	object 		A KCommand object
	 * @param 	integer		The command priority, usually between 1 (high priority) and 5 (lowest), default is 3
	 * @return	 KCommandChain
	 */
	public function enqueue( KCommandInterface $cmd, $priority = KCommand::PRIORITY_NORMAL)
	{
		if($handle = $cmd->getHandle()) 
		{
			$this->_command->offsetSet($handle, $cmd);
		
			$this->_priority->offsetSet($handle, $priority);
			$this->_priority->asort(); //sort the entries by priority
		}
		
		return $this;
  	}
  	
	/**
	 * Remove a command from the chain
	 * 
	 * @param 	object 		A KCommand object
	 * @param 	integer		The command priority
	 * @return 	KCommandChain
	 */
	public function dequeue( KCommandInterface $cmd)
	{
		if($handle = $cmd->getHandle())
		{
			if($this->_command->offsetExists($handle)) 
			{
				$this->_command->offsetUnset($handle);
				$this->_priority->offsetUnSet($handle);	
			}
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
  		if($this->_enabled)
  		{
  			$iterator = $this->_priority->getIterator();
  			
  			//Store a reference to the active context
  			$this->_context = $context;
  		
			while($iterator->valid()) 
			{
    			$cmd = $this->_command[ $iterator->key()];
    		
				if ( $cmd->execute( $name, $context ) === false) {
      				return false;
      			}

    			$iterator->next();
			}
  		}
		
		return true;
  	}
  	
  	/**
	 * Enable the chain
	 *
	 * @return	void
	 */
  	public function enable()
  	{
  		$this->_enabled = true;
  		return $this;
  	}
  	
  	/**
	 * Disable the chain
	 * 
	 * If the chain is disabled running the chain will always return TRUE
	 *
	 * @return	void
	 */
	public function disable()
  	{
  		$this->_enabled = false;
  		return $this;
  	}
  	
  	/**
	 * Set the priority of a command
	 * 
	 * @param object 	A KCommand object 
	 * @param integer	The command priority
	 * @return KCommandChain
	 */
  	public function setPriority(KCommandInterface $cmd, $priority)
  	{
  		if($handle = $cmd->getHandle())
  		{
			if($this->_priority->offsetExists($handle)) {
				$this->_priority->offsetSet($handle, $priority);
			}
  		}
		
		return $this;
  	}
  	
  	/**
	 * Get the priority of a command
	 * 
	 * @param object 	A KCommand object
	 * @param integer	The command priority
	 * @return	integer The command priority
	 */
  	public function getPriority(KCommandInterface $cmd)
  	{
  		if($handle = $cmd->getHandle())
  		{
  			$result = null;
  			if($this->_priority->offsetExist($handle)) {
				$result = $this->_priority->offsetGet($handle);
			}
  		}	
		
		return $result;
  	}
  	
	/**
	 * Factory method for a command context.
	 * 
	 * @return	KCommandContext
	 */
  	public function getContext()
  	{
		$context = new KCommandContext();	
  		return $context;
  	}
}