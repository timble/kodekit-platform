<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	(C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Rowset Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @uses 		KMixinClass
 */
abstract class KDatabaseRowsetAbstract extends KObjectArray implements KFactoryIdentifiable
{
	/**
     * KDatabaseTableAbstract parent class or instance.
     *
     * @var object
     */
    protected $_table;

    /**
	 * The object identifier
	 *
	 * @var KIdentifierInterface
	 */
	protected $_identifier;

	 /**
     * Constructor
     *
     * @param 	array	Options containing 'table', 'name'
     */
    public function __construct(array $options = array())
    {
        // Allow the identifier to be used in the initalise function
        $this->_identifier = $options['identifier'];

  		parent::__construct($options);      
  
		// Set the table indentifier
    	if(isset($options['table'])) {
			$this->setTable($options['table']);
		}
		
		// Insert the data, if exists
		if(!empty($options['data'])) {
			$this->insert($options['data']);
		}
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
        $options = parent::_initialize($options);
    	
    	$defaults = array(
            'table'      => null,
        	'identifier' => null,
        	'data'		 => null
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Get the identifier
	 *
	 * @return 	KIdentifierInterface
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Get the identifier for the table with the same name
	 *
	 * @return	KIdentifierInterface
	 */
	final public function getTable()
	{
		if(!$this->_table)
		{
			$identifier 		= clone $this->_identifier;
			$identifier->name	= KInflector::tableize($identifier->name);
			$identifier->path	= array('table');
		
			$this->_table = $identifier;
		}
       	
		return $this->_table;
	}

	/**
	 * Method to set a table object attached to the rowset
	 *
	  * @param	mixed	An object that implements KFactoryIdentifiable, an object that 
	 *                  implements KIndentifierInterface or valid identifier string
	 * @throws	KDatabaseRowsetException	If the identifier is not a table identifier
	 * @return	KDatabaseRowsetAbstract
	 */
	public function setTable($table)
	{
		$identifier = KFactory::identify($table);

		if($identifier->path[0] != 'table') {
			throw new KDatabaseRowsetException('Identifier: '.$identifier.' is not a table identifier');
		}
		
		$this->_table = $identifier;
		return $this;
	}

	/**
     * Returns a KDatabaseRow from a known position or based on a key/value pair
     *
     * @param 	string 	The position or the key to search for
     * @param 	mixed  	The value to search for
     * @return KDatabaseRowAbstract
     */
    public function find($key, $value = null)
    {
    	if(!is_null($value))
    	{
    		$result = KFactory::tmp(KFactory::get($this->getTable())->getRow(), array('table' => $this->getTable()));

    		$this->rewind();

    		while($this->valid())
			{
				$row = $this->current();
				if($row->$key == $value) {
					$result = $row;
					break;
				}
    			$this->next();
			}
    	} 
    	else $result = $this[$key];
    	
		return $result;
    }
    
	/**
     * Saves all rows in the rowset to the database
     *
     * @return KDatabaseRowsetAbstract
     */
    public function save()
    {
    	$this->rewind();
    	
    	while($this->valid())
		{
			$row = $this->current();
			$row->save();
    		$this->next();
		}
		
        return $this;
    }
    
	/**
     * Deletes all rows in the rowset from the database
     *
     * @return KDatabaseRowsetAbstract
     */
    public function delete()
    {
    	$this->rewind();
    	
    	while($this->valid())
		{
			$row = $this->current();
			$row->delete();
    		$this->next();
		}
		
        return $this;
    }
    
	/**
     * Reset all rows in the rowset
     *
     * @return KDatabaseRowsetAbstract
     */
    public function reset()
    {
    	$this->rewind();
    	
    	while($this->valid())
		{
			$row = $this->current();
			$row->reset();
    		$this->next();
		}
		
        return $this;
    }
    
	/**
     * Insert a new row, a list of rows or an empty row in the rowset
     *
     * @param   array|object 	Either and associative array, a KDatabaseRow object or object
     * @return KDatabaseRowsetAbstract
     */
    public function insert($data = array())
    {
    	$prototype = KFactory::tmp(KFactory::get($this->getTable())->getRow(), array('table' => $this->getTable()));
		$result = array();
		
		if(empty($data))
		{
			$new = clone $prototype;
        	$result[] = $new;
		}
		
		if(is_object($data))
		{
			if(!$row instanceof KDatabaseRowAbstract) 
			{
				$new = clone $prototype;
        		$new->setData($data);
        		$result[] = $new;
			} 
			else $result[] = $data;
		}
		
		if(is_array($data))
		{
			foreach($data as $k => $row)
			{
				if(!$row instanceof KDatabaseRowAbstract) 
				{
					$new = clone $prototype;
        			$new->setData($row);
        			$result[] = $new;
				
				} 
				else $result[] = $row;
			}
		}
		
		return parent::setArray($result);
    }

	/**
     * Returns all data as an array.
     *
     * This works only if we have iterated through the result set once to
     * instantiate the rows.
     *
     * @return array
     */
    public function getData()
    {
    	$result = array();
    	foreach ($this as $i => $row) {
            $result[$i] = $row->getData();
        }
        return $result;
    }
    
	/**
  	 * Set the row data based on a named array/hash
  	 *
  	 * @param   mixed 	Either and associative array, a KDatabaseRow object or object
 	 * @return 	KDatabaseRowsetAbstract
  	 */
  	 public function setData( $data )
  	 {
  	 	foreach ($this as $i => $row) {
  	 		$row->setData($data);
        }
        
        return $this;
	}
	
	/**
     * Forward the call to each row
     *
   	 * @param  string 	The function name
	 * @param  array  	The function arguments
	 * @throws BadMethodCallException 	If method could not be found
	 * @return mixed The result of the function
     */
    public function __call($method, array $arguments)
    {
    	foreach ($this as $i => $row) {
            $row->__call($method, $arguments);
        }
    	
       return parent::__call($method, $arguments);
    }
}