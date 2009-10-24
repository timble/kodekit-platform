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
     * Name of the class of the KDatabaseTableAbstract object.
     *
     * @var string
     */
    protected $_table_class;

    /**
	 * The object identifier
	 *
	 * @var KFactoryIdentifierInterface
	 */
	protected $_identifier;

	 /**
     * Constructor
     *
     * @param 	array	Options containing 'table', 'name'
     */
    public function __construct(array $options = array())
    {
        // Set the objects identifier
        $this->_identifier = $options['identifier'];

    	// Initialize the options
        $options  = $this->_initialize($options);

		// Set table object and class name
		$this->_table_class  = clone $this->_identifier;
		$this->_table_class->path = array('table');
		$this->_table       = isset($options['table']) ? $options['table'] : KFactory::get($this->_table_class);

		// Set the data
		$this->setArray($options['data']);
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
            'table'      => null,
        	'identifier' => null,
        	'data'		 => array()
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Get the identifier
	 *
	 * @return 	KFactoryIdentifierInterface A KFactoryIdentifier object
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	public function setArray($rows)
	{
		$prototype = $this->_table->fetchRow();
		$result = array();
		foreach($rows as $k => $row)
		{
			if($row instanceof KDatabaseRowAbstract) 
			{
				$result[] = $row;
			} 
			else 
			{
				// cloning is faster than instantiation
				$new = clone $prototype;
        		$new->setData($row);
        		$result[] = $new;
			}

		}
		return parent::setArray($result);
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
   		$result = $this->_table->fetchRow();

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
            $result[$i] = is_array($row) ? $row :  $row->getData();
        }
        return $result;
    }
}