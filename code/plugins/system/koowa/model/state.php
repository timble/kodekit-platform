<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Model
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * State Model
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Model
 */
class KModelState extends KModelAbstract
{
	/**
	 * Initializes the options for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return  array   void
	 */
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
            'state'      => array(),
       	));
    }

	/**
     * Get a state value
     *
     * @param  	string 	The user-specified state name.
     * @return 	string 	The corresponding state value or NULL if the state doesn't exist
     */
    public function __get($name)
    {
    	if(isset($this->_state[$name])) {
    		return $this->_state[$name]->value;
    	}

    	return null;
    }

    /**
     * Set state value
     *
     * @param  	string 	The user-specified state name.
     * @param  	mixed  	The user-specified state value.
     * @return 	void
     */
    public function __set($name, $value)
    {
    	if(isset($this->_state[$name])) {
    		$this->_state[$name]->value = $value;
    	}
   }

	/**
     * Test existence of a state
     *
     * @param  string  The column key.
     * @return boolean
     */
    public function __isset($name)
    {
    	return array_key_exists($name, $this->_state);
    }

    /**
     * Unset a state value
     *
     * @param	string  The column key.
     * @return	void
     */
    public function __unset($name)
    {
    	if(isset($this->_state[$name])) {
    		$this->_state[$name]->value = $this->_state[$name]->default;
    	}
    }

	/**
     * Insert a new state
     *
     * @param   string		The name of the state
     * @param   mixed		Filter(s), can be a KFilterInterface object, a filter name or an array of filter names
     * @param   mixed  		The default value of the state
     * @param   boolean 	TRUE if the state uniquely indetifies an enitity, FALSE otherwise. Default FALSE.
     * @return  KModelState
     */
    public function insert($name, $filter, $default = null, $unique = false)
    {
    	if(!isset($this->_state[$name]))
    	{
    		$state = new stdClass();
    		$state->name   = $name;
    		$state->filter = $filter;
    		$state->value  = $default;
    		$state->unique = $unique;
    		$this->_state[$name] = $state;
    	}

        return $this;
    }

	/**
     * Remove an existing state
     *
     * @param   string		The name of the state
     * @return  KModelState
     */
    public function remove( $name )
    {
    	unset($this->_state[$name]);
        return $this;
    }

	/**
     * Reset all cached data
     *
     * @return KModelState
     */
    public function reset()
    {
    	unset($this->_state);  	
    	return $this;
    }
    
	/**
     * Set the state data
     *
     * @param   array|object	An associative array of state values by name
     * @return  KModelState
     */
    public function setData(array $data)
    {
		// Filter data
		foreach($data as $key => $value)
		{
			if(isset($this->_state[$key]))
    		{
    			$filter = $this->_state[$key]->filter;

    			if(!($filter instanceof KFilterInterface)) {
					$filter = KFilter::instantiate(array('filter' => $filter));
				}

    			$this->_state[$key]->value = $filter->sanitize($value);
    		}
		}

        return $this;
    }

    /**
     * Get the state data
     * 
     * This function only returns states that have been been set.
     *
     * @param   boolean	If TRUE only retrieve unique state values, default FALSE
     * @return  array 	An associative array of state values by name
     */
    public function getData($unique = false)
    {
        $data = array();

   		foreach ($this->_state as $name => $state) 
   		{
            if($state->value)
            {
           		 if($unique) 
           		 {
   					if($state->unique) {
           		 		$data[$name] = $state->value;
   					}
   					
            	} else $data[$name] = $state->value;	
            }
        }
       
        return $data;
    }
    
    /**
     * Check if the state information is unique 
     * 
     * @return  boolean TRUE if the state is unique, otherwise FALSE.
     */
    public function isUnique()
    {
    	return (bool) count($this->getData(true));
    }
}