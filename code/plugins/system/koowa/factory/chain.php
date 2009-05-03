<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage	Chain
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Factory Command Chain
 *
 * The factory chain overrides the run method to be able to halt the chain
 * when a command return a value. If the command returns false the chain 
 * will keep running.
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Factory
 */
class KFactoryChain extends KPatternCommandChain
{
  	/**
	 * Run the commands in the chain
	 * 
	 * If a command returns not false the exection is halted
	 * 
	 * @param string  The command name
	 * @param mixed   The command arguments
	 *
	 * @return string|false
	 */
  	final public function run( $name, $args )
  	{
  		$iterator = $this->_priority->getIterator();

		while($iterator->valid()) 
		{
    		$cmd = $this->_command[ $iterator->key()];
   
			$result = $cmd->execute( $name, $args );
    		if ($result !== false) {
      			return $result; //halt execution and return result
      		}

    		$iterator->next();
		}
		
		return false;
  	}
}
