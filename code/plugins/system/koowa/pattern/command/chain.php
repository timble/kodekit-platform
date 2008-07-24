<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @package		Koowa_Pattern
 * @subpackage	Command
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Command Chain class
 * 
 * The command queue implements a double linked list. The command handle is used as
 * the key. Class can be used as a mixin in classes that want to implement a chain 
 * of responsability or chain of command pattern
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_Pattern
 * @subpackage  Command
 */
class KPatternCommandChain extends KObject
{
	/**
	 * Command list
	 *
	 * @var array
	 */
	protected $_command;
	
	
	/**
	 * Priority list
	 *
	 * @var array
	 */
	protected $_priority;

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
	 * @param object 	$cmd		A KPatternCommandHandler 
	 * @param integer	$priority	The command priority
	 *
	 * @return	void
	 */
	public function enqueue( KPatternCommandHandler $cmd, $priority = 1)
	{
		$handle = $cmd->getHandle(); //get the object handle
		
		$this->_command->offsetSet($handle, $cmd);
		
		$this->_priority->offsetSet($handle, $priority);
		$this->_priority->asort(); //sort the entries by priority
  	}
  	
	/**
	 * Attach a command to the chain
	 * 
	 * @param object 	$cmd		A KPatternCommandHandler 
	 * @param integer	$priority	The command priority
	 *
	 * @return 	boolean True if the command handler was detached
	 */
	public function dequeue( KPatternCommandHandler $cmd)
	{
		$handle = $cmd->getHandle(); //get the object handle
		
		$result = false;
  		if($this->_command->offsetExist($handle)) {
			$this->_command->offsetUnset($handle);	
  			$result = true;
		}

		return $result;
  	}

  	/**
	 * Run the commands in the chain
	 * 
	 * If a command return false the executing is halted
	 * 
	 * @param string  $name		The command name
	 * @param object  $args		The command arguments
	 *
	 * @return	void
	 */
  	public function execute( $name, $args )
  	{
  		$iterator = $this->_priority->getIterator();

		while($iterator->valid()) 
		{
    		$cmd = $this->_command[ $iterator->key()];
    		
			if ( $cmd->execute( $name, $args ) === false) {
      			return false;
      		}

    		$iterator->next();
		}
		
		return true;
  	}
  	
  	/**
	 * Set the priority of a command
	 * 
	 * @param object 	$cmd		A KPatternCommandHandler 
	 * @param integer	$priority	The command priority
	 *
	 * @return	void
	 */
  	public function setPriority(KPatternCommandHandler $cmd, $priority)
  	{
  		$hanlde = $cmd->getHandle(); //get the object handle
		
		if($this->_priority->offsetExists($handle)) {
			$this->_priority->offsetSet($handle, $priority);
		}
		
		return $this;
  	}
  	
  	/**
	 * Get the priority of a command
	 * 
	 * @param object 	$cmd		A KPatternCommandHandler 
	 * @param integer	$priority	The command priority
	 *
	 * @return	integer The command priority
	 */
  	public function getPriority(KPatternCommandHandler $cmd)
  	{
  		$hanlde = $cmd->getHandle(); //get the object handle
  	
  		$result = null;
  		if($this->_priority->offsetExist($handle)) {
			$result = $this->_priority->offsetGet($handle);
		}
		
		return $result;
  	}
}
