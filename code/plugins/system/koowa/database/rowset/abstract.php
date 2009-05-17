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
abstract class KDatabaseRowsetAbstract extends KObjectArray
{
	/**
	 * Original data passed to the object
	 * 
	 * @var 	array 
	 */
	protected $_data = array();
	
	/**
     * KDatabaseTableAbstract parent class or instance.
     *
     * @var object
     */
    protected $_table;

	/**
     * Name of the class of the KDatabaseTableAbstract object.
     *
     * @var string
     */
    protected $_table_class;

    /**
     * Empty row to use for cloning
     *
     * @var object	KDatabaseRowAbstract
     */
    protected $_empty_row;
    
	 /**
     * Constructor
     *
     * @param 	array	Options containing 'table', 'name'
     */
    public function __construct(array $options = array())
    {
        // Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KMixinClass
        $this->mixin(new KMixinClass(array('mixer' => $this, 'name_base' => 'Rowset')));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Set table object and class name
		$this->_table_class  = 'com.'.$this->getClassName('prefix').'.table.'.$this->getClassName('suffix');
		$this->_table       = isset($options['table']) ? $options['table'] : KFactory::get($this->_table_class);

		// Set the data
		if(isset($options['data']))  {
			$this->_data = $options['data'];
		}
		
		// Count the data
		$this->resetCount();
		
		// Instantiate an empty row to use for cloning later
		$this->_empty_row = $this->_table->fetchRow();
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
            'name'      => array(
                        'prefix'    => 'k',
                        'base'      => 'rowset',
                        'suffix'    => 'default'
                        ),
            'table'     => null
        );

        return array_merge($defaults, $options);
    }

    
	/**
     * Overridden current() method
     * 
     * Used to delay de creation of KDatabaseRow objects, for performance reasons
     *
     * @return KDatabaseRowAbstract Current element from the collection
     */
    public function current()
    {
    	if ($this->valid() === false) {
            return null;
        }

		// do we already have a row object for this position?
        if (!isset($this[$this->key()])) 
        {
        	// cloning is faster than instantiating
        	$row = clone $this->_empty_row;
        	$row->setProperties($this->_data[$this->key()]);
            parent::offsetSet($this->key(), $row);
        }

    	// return the row object
        return parent::offsetGet($this->key());
    }
    
    /**
     * Overridden offsetSet() method
     *
     * @param 	int 	The offset of the item
     * @param 	mixed	The item's value
     * @return  this
     */
	public function offsetSet($offset, $value) 
	{
		if($value instanceof KDatabaseRowAbstract) {
			$value = $value->toArray();
		}
		
		if(empty($offset)) {
			$this->_data[] = $value;
		} else {
			$this->_data[$offset] = $value;
		}

		$this->resetCount();
		return $this;
	}
	
 	/**
     * Overridden offsetSet() method
     * 
     * All numerical array keys will be modified to start counting from zero 
     * while literal keys won't be touched.
     * 
     * @param 	int 	The offset of the item
     * @return 	this
     */
	public function offsetUnset($offset)
	{
		//We need to use array_splice instead of unset to reset the keys
		array_splice($this->_data, $offset, 1);
        return parent::offsetUnset($offset);
	}
	
	/**
     * Overridden resetCount() method
     *
     * @return this
     */
    public function resetCount()
    {
    	$this->setCount(count($this->_data));
    	return $this;
    }
    
	/**
     * Returns the table object, or null if this is disconnected row
     *
     * @return KDatabaseTableAbstract
     */
    public function getTable()
    {
        return $this->_table;
    }

	/**
     * Query the class name of the Table object for which this row was 
     * created.
     *
     * @return string
     */
    public function getTableClass()
    {
        return $this->_table_class;
    }

	 /**
     * Returns a KDatabaseRow from a known position into the Iterator
     *
     * @param int $position the position of the row expected
     * @param bool $seek wether or not seek the iterator to that position after
     * @return KDatabaseRowAbstract
     * @throws KDatabaseRowsetException
     */
    public function getRow($position, $seek = false)
    {
        $key = $this->key();
        try 
        {
            $this->seek($position);
            $row = $this->current();
        } 
        catch (KDatabaseRowsetException $e) {
            throw new KDatabaseRowsetException('No row could be found at position ' . (int) $position);
        }

        if ($seek == false) {
            $this->seek($key);
        }
        return $row;
    }
    
	/**
     * Returns a row from a known position
     *
     * @param 	string 	The key to search for
     * @param 	mixed  	The value to search for
     * @return KDatabaseRowAbstract
     */
    public function findRow($key, $value)
    {
   		$result = $this->_empty_row;
    	
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
		
		return $result;
    }

	/**
     * Returns all data as an array.
     *
     * This works only if we have iterated through the result set once to 
     * instantiate the rows.
     *
     * @return array
     */
    public function toArray()
    {
    	$result = array();
    	foreach ($this->_data as $i => $row) {
            $result[$i] = is_array($row) ? $row :  $row->toArray();
        }
        return $result;
    }
}