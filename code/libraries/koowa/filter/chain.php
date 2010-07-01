<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Filter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
class KFilterChain extends KCommandChain
{
	/**
	 * Run the commands in the chain
	 * 
	 * @param string  The filter name
	 * @param array   The data to be filtered
	 * @return	mixed
	 */
  	final public function run( $name, KCommandContext $context )
  	{
  		$function = '_'.$name;
  		$result =  $this->$function($context);
  		return $result;
  	}

	/**
	 * Validate the data
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the data is valid
	 */
  	final protected function _validate( KCommandContext $context )
  	{
  		$iterator = $this->_priority->getIterator();

		while($iterator->valid()) 
		{
    		$cmd = $this->_command[ $iterator->key()];
   
			if ( $cmd->execute( 'validate', $context ) === false) {
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
  	final protected function _sanitize( KCommandContext $context )
  	{
  		$iterator = $this->_priority->getIterator();

		while($iterator->valid()) 
		{
    		$cmd = $this->_command[ $iterator->key()];
			$context->data = $cmd->execute( 'sanitize', $context ); 
    		$iterator->next();
		}
		
		return $context->data;
  	}
}