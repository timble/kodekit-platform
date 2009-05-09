<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Filter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Filter Chain
 *
 * The filter chain overrides the run method to implement a seperate
 * validate and santize method
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterChain extends KPatternCommandChain
{
	/**
	 * Run the commands in the chain
	 * 
	 * @param string  $name		The command name
	 * @param mixed   $args		The command arguments
	 * @return	mixed
	 */
  	final public function run( $name, $args )
  	{
  		$function = '_'.$name;
  		return $this->$name($args);
  	}

	/**
	 * Validate the data
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the data is valid
	 */
  	final protected function _validate( $data )
  	{
  		$iterator = $this->_priority->getIterator();

		while($iterator->valid()) 
		{
    		$cmd = $this->_command[ $iterator->key()];
   
			if ( $cmd->execute( 'validate', $data ) === false) {
      			return false;
      		}
    		
    		$iterator->next();
		}
		
		return true;
  	}
  	
	/**
	 * Sanitize the data
	 *
	 * @param	scalar	Valuae to be sanitized
	 * @return	mixed
	 */
  	final protected function _sanitize( $data )
  	{
  		$iterator = $this->_priority->getIterator();

		while($iterator->valid()) 
		{
    		$cmd = $this->_command[ $iterator->key()];
			$data = $cmd->execute( 'sanitize', $data ); 
    		$iterator->next();
		}
		
		return $data;
  	}
}
