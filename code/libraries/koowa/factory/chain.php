<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage	Chain
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 */
class KFactoryChain extends KCommandChain
{
  	/**
	 * Run the commands in the chain
	 *
	 * If a command returns not false the exection is halted
	 *
	 * @param string  The command name
	 * @param object  The command context
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
  	final public function run( $name, KCommandContext $context )
  	{	
  		$iterator = $this->_priority->getIterator();

		while($iterator->valid())
		{
    		$cmd = $this->_command[ $iterator->key()];

			$result = $cmd->execute( $name, $context );
    		if ($result !== false) 
    		{
      			$this->_context = null;
    			return $result; //halt execution and return result
      		}

    		$iterator->next();
		}

		$this->_context = null;
		return false;
  	}
}