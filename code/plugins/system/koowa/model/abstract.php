<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Model
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
abstract class KModelAbstract extends KObject implements KFactoryIdentifiable
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
	 * The object identifier
	 *
	 * @var object 
	 */
	protected $_identifier = null;

	/**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
		// Set the objects identifier
        $this->_identifier = $options['identifier'];
		
		// Initialize the options
		$options  = $this->_initialize($options);
		
		// Set the state
		$this->_state = $options['state'];
	}

	/**
	 * Initializes the options for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param   array   Options
	 * @return  array   Options
	 */
	protected function _initialize(array $options)
	{
		$defaults = array(
            'state'      => KFactory::tmp('lib.koowa.model.state'),
			'identifier' => null
       	);

        return array_merge($defaults, $options);
    }

    /**
	 * Get the identifier
	 *
	 * @return 	object A KFactoryIdentifier object
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
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
}