<?php
/**
 * @version		$Id$
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	(C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Rowset Class
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @uses 		KPatternClass
 */
abstract class KDatabaseRowsetAbstract extends KObject implements SeekableIterator, Countable
{
   /**
     * The original data for each row.
     *
     * @var array
     */
    protected $_data = array();

	 /**
     * Collection of instantiated KDatabaseRow objects.
     *
     * @var array
     */
    protected $_rows = array();

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
     * Iterator pointer.
     *
     * @var integer
     */
    protected $_pointer = 0;

    /**
     * How many data rows there are.
     *
     * @var integer
     */
    protected $_count = 0;

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
    public function __construct($options = array())
    {
        // Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KPatternClass
        $this->mixin(new KPatternClass($this, 'Rowset'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Set table object and class name
		$this->_tableClass  = ucfirst($this->getClassName('prefix')).'Table'.ucfirst($this->getClassName('suffix'));
		$this->_table       = isset($options['table']) ? $options['table'] : KFactory::get($this->_tableClass);

		// Set data
		if(isset($options['data']))  {
			$this->_data 	= $options['data'];
			$this->_count 	= count($this->_data);
		}
		
		// Instantiate an empty row to use for cloning later
		$this->_emptyRow = $this->_table->fetchNew();
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize($options)
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
     * Rewind the Iterator to the first element.
     *
     * Similar to the reset() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return KDatabaseRowsetAbstract Fluent interface.
     */
    public function rewind()
    {
        $this->_pointer = 0;
        return $this;
    }

	/**
     * Return the current element.
     *
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     * @return KDatabaseRowsetAbstract current element from the collection
     */
    public function current()
    {
    	if ($this->valid() === false) {
            return null;
        }

		// do we already have a row object for this position?
        if (!isset($this->_rows[$this->_pointer])) {
        	// cloning is faster than instantiating
            $this->_rows[$this->_pointer] = clone $this->_emptyRow;
            $this->_rows[$this->_pointer]->setProperties($this->_data[$this->_pointer]);
        }

    	// return the row object
        return $this->_rows[$this->_pointer];
    }

	/**
     * Return the identifying key of the current element.
     *
     * Similar to the key() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return int
     */
    public function key()
    {
    	return $this->_pointer;
    }

	/**
     * Move forward to next element.
     *
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return	this
     */
    public function next()
    {
    	++$this->_pointer;
    	return $this;
    }

	/**
     * Check if there is a current element after calls to rewind() or next().
     *
     * Used to check if we've iterated to the end of the collection.
     * Required by interface Iterator.
     *
     * @return bool False if there's nothing more to iterate over
     */
    public function valid()
    {
        return $this->_pointer < $this->_count;
    }

	/**
     * Returns the number of elements in the collection.
     *
     * Implements Countable::count()
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

	/**
     * Take the Iterator to position $position
     * Required by interface SeekableIterator.
     *
     * @param int $position the position to seek to
     * @return KDatabaseRowsetAbstract
     * @throws KDatabaseRowsetException
     */
    public function seek($position)
    {
        $position = (int) $position;
        if ($position < 0 || $position > $this->_count) {
            throw new KDatabaseRowsetException("Illegal index $position");
        }
        $this->_pointer = $position;
        return $this;
    }

	/**
     * Returns all data as an array.
     *
     * This works only if we have iterated through the result set once to instantiate the rows.
     * Updates the $_data property with current row object values.
     *
     * @return array
     */
    public function toArray()
    {
        foreach ($this->_rows as $i => $row) {
            $this->_data[$i] = $row->toArray();
        }
        return $this->_data;
    }

}