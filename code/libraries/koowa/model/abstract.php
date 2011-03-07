<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Model
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Model Class
 *
 * @author		Johan Janssens <johan@nooku.org>
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
	 * Model column data
	 *
	 * @var mixed
	 */
	protected $_column;

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
    		$property = (array) KConfig::toData($property);
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
     * @param   string  The name of the property
     * @param   mixed   The default value
     * @return  mixed   The value of the property, an associative array or NULL
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
     * Reset all cached data and reset the model state to it's default
     * 
     * @param   boolean If TRUE use defaults when resetting. Default is TRUE
     * @return KModelAbstract
     */
    public function reset($default = true)
    {
        unset($this->_list);
        unset($this->_item);
        unset($this->_total);
        
        $this->_state->reset($default);

        return $this;
    }

    /**
     * Method to get state object
     *
     * @return  object  The state object
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
     * Get the distinct values of a column
     *
     * @return object
     */
    public function getColumn($column)
    {   
        return $this->_column[$column];
    }

    /**
     * Supports a simple form Fluent Interfaces. Allows you to set states by
     * using the state name as the method name.
     *
     * For example : $model->sort('name')->limit(10)->getList();
     *
     * @param   string  Method name
     * @param   array   Array containing all the arguments for the original call
     * @return  KModelAbstract
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