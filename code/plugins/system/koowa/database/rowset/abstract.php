<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	(C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Rowset Class
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @author		Johan Janssens <johan@joomlatools.org>
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
    protected $_tableClass;


    /**
     * Empty row to use for cloning
     *
     * @var object	KDatabaseRowAbstract
     */
    protected $_emptyRow;
    
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
        $this->mixin(new KMixinClass($this, 'Rowset'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Set table object and class name
		$this->_tableClass  = 'com.'.$this->getClassName('prefix').'.table.'.$this->getClassName('suffix');
		$this->_table       = isset($options['table']) ? $options['table'] : KFactory::get($this->_tableClass);

		// Set the data
		if(isset($options['data']))  {
			$this->_data = $options['data'];
		}
		
		// Count the data
		$this->resetCount();
		
		// Instantiate an empty row to use for cloning later
		$this->_emptyRow = $this->_table->fetchRow();
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
            'base_path' => null,
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
        	$row = clone $this->_emptyRow;
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
     * @return  object KDatabaseRowsetAbstract
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
     * @param 	int 	The offset of the item
     * @return 	object KDatabaseTRowsetAbstract
     */
	public function offsetUnset($offset)
	{
        unset($this->_data[$offset]);
		return parent::offsetUnset($offset);
	}
	
	/**
     * Overridden resetCount() method
     *
     * @return 	object KDatabaseTRowsetAbstract
     */
    public function resetCount()
    {
    	$this->setCount(count($this->_data));
    	return $this;
    }
    
	/**
     * Returns the table object, or null if this is disconnected row
     *
     * @return object|null 	KDatabaseTableAbstract
     */
    public function getTable()
    {
        return $this->_table;
    }

	/**
     * Query the class name of the Table object for which this
     * Row was created.
     *
     * @return string
     */
    public function getTableClass()
    {
        return $this->_tableClass;
    }

	 /**
     * Returns a KDatabaseRow from a known position into the Iterator
     *
     * @param int $position the position of the row expected
     * @param bool $seek wether or not seek the iterator to that position after
     * @return KDatabaseRow
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
     * Returns a KDatabaseRow from a known position into the Iterator
     *
     * @param string $key   the key to search for
     * @param mixed  $value the value to search for
     * @return KDatabaseRow
     */
    public function findRow($key, $value)
    {
   		$result = null;
    	
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
    	foreach ($this as $i => $row) {
            $result[$i] = $row->toArray();
        }
        return $result;
    }
}