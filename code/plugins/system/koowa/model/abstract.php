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
 * Abstract Model Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Model
 * @uses		KObject
 */
abstract class KModelAbstract extends KObject implements KObjectIdentifiable
{
	/**
	 * A state object
	 *
	 * @var object
	 */
	protected $_state;

	/**
	 * List total
	 *
	 * @var integer
	 */
	protected $_total;

	/**
	 * Model list data
	 *
	 * @var array
	 */
	protected $_list;

	/**
	 * Model item data
	 *
	 * @var mixed
	 */
	protected $_item;
	

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config = null)
	{
        //If no config is passed create it
		if(!isset($config)) $config = new KConfig();
		
		parent::__construct($config);
            
		$this->_state = $config->state;
	}

	/**
	 * Initializes the options for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return  void
	 */
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
            'state'      => KFactory::tmp('lib.koowa.model.state'),
       	));
       	
       	parent::_initialize($config);
    }
    
	/**
	 * Get the object identifier
	 * 
	 * @return	KIdentifier	
	 * @see 	KObjectIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}
    
	/**
     * Set the model state properties
     * 
     * This function overloads the KObject::set() function and only acts on state properties.
     *
     * @param   string|array|object	The name of the property, an associative array or an object
     * @param   mixed  				The value of the property
     * @return	KModelAbstract
     */
    public function set( $property, $value = null )
    {
    	if(is_object($property)) {
    		$property = (array) $property;
    	}
    	
    	if(is_array($property)) {
        	$this->_state->setData($property);
        } else {
        	$this->_state->$property = $value;
        }
    	
        return $this;
    }

    /**
     * Get the model state properties
     * 
     * This function overloads the KObject::get() function and only acts on state 
     * properties
     * 
     * If no property name is given then the function will return an associative
     * array of all properties.
     * 
     * If the property does not exist and a  default value is specified this is
     * returned, otherwise the function return NULL.
     *
     * @param   string	The name of the property
     * @param   mixed  	The default value
     * @return  mixed 	The value of the property, an associative array or NULL
     */
    public function get($property = null, $default = null)
    {
        $result = $default;
        	
    	if(is_null($property)) {
        	$result = $this->_state->getData();
        } 
        else 
        {
    		if(isset($this->_state->$property)) {
        		$result = $this->_state->$property;
    		}
        }
        
        return $result;
    }

    /**
     * Reset all cached data
     *
     * @return KModelAbstract
     */
    public function reset()
    {
    	unset($this->_list);
    	unset($this->_item);
    	unset($this->_total);
    	
    	return $this;
    }

	/**
	 * Method to get state object
	 *
	 * @return	object	The state object
	 */
	public function getState()
	{
		return $this->_state;
	}
	
/**
	 * Method to get a ite
	 *
	 * @return  object
	 */
	public function getItem()
	{
		return $this->_item;
	}

	/**
	 * Get a list of items
	 *
	 * @return  object
	 */
	public function getList()
	{
		return $this->_list;
	}

	/**
	 * Get the total amount of items
	 *
	 * @return  int
	 */
	public function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Supports a simple form Fluent Interfaces. Allows you to set states by 
	 * using the state name as the method name. 
	 * 
	 * For example : $model->order('name')->limit(10)->getList();
	 * 
	 * @param	string	Method name
	 * @param	array	Array containing all the arguments for the original call
	 * @return	KModelAbstract
	 * 
	 * @see http://martinfowler.com/bliki/FluentInterface.html
	 */
	public function __call($method, $args)
	{
		if(isset($this->_state->$method)) {
			return $this->set($method, $args[0]);	
		} 
		
		return parent::__call($method, $args);
	}
}