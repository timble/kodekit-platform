<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Abstract filter.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
abstract class KFilterAbstract implements KFilterInterface
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
	 * @param 	object	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config) 
	{
		 $this->_chain = new KFilterChain();
		 $this->addFilter($this);
	}
	
	/**
	 * Generic Command handler
	 * 
	 * @param string  The command name
	 * @param object  The command context
	 *
	 * @return object
	 */
	final public function execute($name, KCommandContext $context) 
	{	
		$function = '_'.$name;
		return $this->$function($context->data);
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
			$context = $this->_chain->getContext();
			$context->data = $data;
			
			$result = $this->_chain->run('validate', $context);
			
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
			$context = $this->_chain->getContext();
			$context->data = $data;
			
			$data = $this->_chain->run('sanitize', $context);
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
	public function addFilter(KFilterInterface $filter, $priority = KCommandChain::PRIORITY_NORMAL)
	{	
		$this->_chain->enqueue($filter, $priority);
		return $this;
	}
	
	/**
	 * Get a handle for this object
	 *
	 * This function returns an unique identifier for the object. This id can be used as
	 * a hash key for storing objects or for identifying an object
	 *
	 * @return string A string that is unique
	 */
	public function getHandle()
	{
		return spl_object_hash( $this );
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