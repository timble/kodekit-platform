<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

/**
 * Abstract filter.
 *
 * @author		Johan Janssens <johan@nooku.org>
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
	 * If the data to be santized or validated if an object or array,
	 * walk over each individual property or element. Default TRUE.
	 *
	 * @var	boolean
	 */
	protected $_walk = true;
	
	/**
	 * Constructor
	 *
	 * @param 	object	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config) 
	{
		 $this->_chain = new KFilterChain();
		 $this->addFilter($this);
		 
		$this->_initialize($config);
	}
	
 	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
    	//do nothing
    }
	
	/**
	 * Command handler
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
		if($this->_walk && (is_array($data) || is_object($data))) 
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
		if($this->_walk && (is_array($data) || is_object($data))) 
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
	public function addFilter(KFilterInterface $filter)
	{	
		$this->_chain->enqueue($filter);
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
	 * Get the priority of the filter
	 *
	 * @return	integer The command priority
	 */
  	public function getPriority()
  	{
  		return KCommand::PRIORITY_NORMAL;
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