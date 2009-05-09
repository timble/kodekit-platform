<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Pattern
 * @subpackage	Command
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Command Chain
 * 
 * The command queue implements a double linked list. The command handle is used as
 * the key.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
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
	 * @param object 	$cmd		A KPatternCommandHandler 
	 * @param integer	$priority	The command priority, usually between 1 (high priority) and 5 (low), default is 3
	 *
	 * @return	this
	 */
	public function enqueue( KPatternCommandInterface $cmd, $priority = 3)
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
	 * @param object 	$cmd		A KPatternCommandHandler 
	 * @param integer	$priority	The command priority
	 *
	 * @return 	boolean True if the command handler was detached
	 */
	public function dequeue( KPatternCommandInterface $cmd)
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
	 * @param mixed   $args		The command arguments
	 *
	 * @return	void
	 */
  	public function run( $name, $args )
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
  	public function setPriority(KPatternCommandInterface $cmd, $priority)
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
  	public function getPriority(KPatternCommandInterface $cmd)
  	{
  		$hanlde = $cmd->getHandle(); //get the object handle
  	
  		$result = null;
  		if($this->_priority->offsetExist($handle)) {
			$result = $this->_priority->offsetGet($handle);
		}
		
		return $result;
  	}
}
