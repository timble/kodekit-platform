<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPL <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.koowa.org
*/

/**
 * Abstract filter.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
abstract class KFilterAbstract extends KObject implements KFilterInterface
{
	/**
	 * The filter chain
	 *
	 * @var	object
	 */
	protected $_chain = null;
	
	/**
	 * Constructor
	 *
	 * @param	array	Options array
	 */
	public function __construct(array $options = array())
	{
		
	}
	
	/**
	 * Generic Command handler
	 * 
	 * @param string  $name		The command name
	 * @param mixed   $args		The command arguments
	 *
	 * @return object
	 */
	final public function execute($name, $args) 
	{	
		$function = '_'.$name;
		return $this->$function($args);
	}

	/**
	 * Validate a variable or data collection
	 *
	 * @param	mixed	Data to be validated
	 * @return	bool	True when the data is valid
	 */
	final public function validate($data)
	{
		if(is_array($data) || is_object($data)) 
		{
			$arr = (array)$data;
			
			foreach($arr as $value) 
			{
				if($this->validate($value) ===  false) {
					return false;
				}
			}
		} 
		else 
		{	
			//Only run the chain if it exists
			$result = isset($this->_chain) ? $this->_chain->run('validate', $data) : $this->_validate($data);
			
			if($result ===  false) {
				return false;
			}
		}
			
		return true;
	}
	
	/**
	 * Sanitize a variable or data collection
	 *
	 * @param	mixed	Data to be sanitized
	 * @return	mixed	The sanitized data
	 */
	public final function sanitize($data)
	{
		if(is_array($data) || is_object($data)) 
		{
			$arr = (array)$data;
				
			foreach($arr as $key => $value) 
			{
				if(is_array($data)) {
					$data[$key] = $this->sanitize($value);
				}
				
				if(is_object($data)) {
					$data->$key = $this->sanitize($value);	
				}
			}
		}
		else
		{
			//Only run the chain if it exists
			$data = isset($this->_chain) ? $this->_chain->run('sanitize', $data) : $this->_sanitize($data);
		}
		
		return $data;
	}
	
	/**
	 * Add a filter based on priority
	 * 
	 * @param object 	A KFilter
	 * @param integer	The filter priority
	 *
	 * @return this
	 */
	public function addFilter(KFilterInterface $filter, $priority = 3)
	{
		//If the chain doesn't exist create it and enqueue this as the first filter
		if(!isset($this->_chain)) 
		{
			$this->_chain = new KFilterChain();
			$this->_chain->enqueue($this, 3);
		}
			
		$this->_chain->enqueue($filter, $priority);
		return $this;
	}


	/**
	 * Validate a variable
	 * 
	 * Variable passed to this function will always be a scalar
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	abstract protected function _validate($value);
	
	/**
	 * Sanitize a variable only
	 * 
	 * Variable passed to this function will always be a scalar
	 *
	 * @param	scalar	Value to be sanitized
	 * @return	mixed
	 */
	abstract protected function _sanitize($value);
}