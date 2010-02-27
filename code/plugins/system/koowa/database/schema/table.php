<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Schema
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Schema Table Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Schema
 */
class KDatabaseSchemaTable extends KObject
{
	/**
	 * Table name
	 * 
	 * @var string
	 */
	public $name;
	
	/**
	 * The storage engine
	 * 
	 * @var string
	 */
	public $engine;
	
	/**
	 * Table type
	 * 
	 * @var	string
	 */
	public $type;
	
	/**
	 * Table size
	 * 
	 * @var integer
	 */
	public $size;
	
	/**
	 * Table next auto increment value
	 * 
	 * @var integer
	 */
	public $autoinc;
	
	/**
	 * The tables character set and collation
	 * 
	 * @var string
	 */
	public $collation;
	
	/**
	 * The tables description
	 * 
	 * @var string
	 */
	public $description;
	
	/**
	 * List of behaviors
	 * 
	 * Public access is allowed via __get() with $behaviors.
	 * 
	 * @var	array
	 */
	protected $_behaviors = array();
	
	/** 
     * Implements the virtual $behaviors property.
     * 
     * The value can be a KDatabaseBehavior object, a behavior name or identifier, an array of 
     * behavior names or identifiers
     * 
     * @param 	string 	The virtual property to set, only accepts 'filter'
     * @param 	string 	Set the virtual property to this value.
     */
    public function __set($key, $value)
    {
    	if ($key == 'behaviors') {
        	$this->_behaviors = (array) $value;
        }
    }
	
    /**
     * Implements access to $_behaviors by reference so that it appears to be 
     * a public $behaviors property.
     * 
     * @param 	string	The virtual property to return, only accepts 'behaviors'
     * @return 	mixed 	The value of the virtual property.
     */
    public function &__get($key)
    {
    	if ($key == 'behaviors') 
        {
       		foreach($this->_behaviors as $key => $identifier)
			{
				if(!($identifier instanceof KDatabaseBehaviorInterface)) 
				{
					if(is_string($identifier) && strpos($identifier, '.') === false ) {
						$identifier = 'lib.koowa.database.behavior.'.$identifier;
					} 
										
					$this->_behaviors[$key] =  KFactory::get($identifier);
				}
			}
				
			return $this->_behaviors;
        }
    }
}