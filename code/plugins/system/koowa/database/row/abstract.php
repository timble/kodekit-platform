<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	(C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Row Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 */
abstract class KDatabaseRowAbstract extends KObject implements KFactoryIdentifiable
{
	/**
     * The data for each column in the row (column_name => value).
     * The keys must match the physical names of columns in the
     * table for which this row is defined.
     *
     * @var array
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
	 * The object identifier
	 *
	 * @var object
	 */
	protected $_identifier = null;

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
		$this->_table_class = $this->_identifier->application.'::com.'.$this->_identifier->package.'.table.'.$this->_identifier->name;
		$this->_table       = isset($options['table']) ? $options['table'] : KFactory::get($this->_table_class);

		// Reset the row
		$this->reset();

		// Set the row data
		if(isset($options['data']))  {
			$this->setProperties($options['data']);
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
        $defaults = array(
            'table'      => null,
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
        return $this->_table_class;
    }

    /**
     * Saves the properties to the database.
     *
     * This performs an intelligent insert/update, and reloads the
     * properties with fresh data from the table on success.
     *
     *
     * @return mixed The primary key value(s), as an associative array if the
     *     			 key is compound, or a scalar if the key is single-column.
     */
    public function save()
    {
        $key = $this->_table->getPrimaryKey();

        $properties = $this->getProperties();

        if(array_key_exists('ordering', $properties) && $properties['ordering'] <= 0) {
        	$properties['ordering'] = $this->getTable()->getMaxOrder();
        }

        if($this->_data[$key]) {
        	$this->_table->update($properties, $this->_data[$key]);
        }
        else
        {
        	if($this->_table->insert($properties)) {
        		$this->id = $this->_table->getDatabase()->getInsertId();
        	}
        }

        return $this;
    }

	/**
     * Deletes existing rows.
     *
     * @return KDatabaseRowAbstract
     */
    public function delete()
    {
		$key = $this->_table->getPrimaryKey();

    	$this->_table->delete($this->_data[$key]);
        return $this;
    }

	/**
     * Resets to the default properties
     *
     * @return KDatabaseRowAbstract
     */
    public function reset()
    {
        $this->_data = $this->_table->getDefaults();
        return $this;
    }

    /**
     * Increase hit counter by 1
     *
     * Requires a hit field to be present in the table
     *
     * @return KDatabaseRowAbstract
     * @throws KDatabaseRowException
     */
	public function hit()
	{
		if (!in_array('hits', $this->_table->getColumns())) {
			throw new KDatabaseRowException("The table ".$this->_table->getName()." doesn't have a 'hits' column.");
		}

		$this->hits++;
		$this->save();

		return $this;
	}

	/**
	 * Move the row up or down in the ordering
	 *
	 * Requires an ordering field to be present in the table
	 *
	 * @param	integer	Amount to move up or down
	 * @return 	KDatabaseRowAbstract
	 * @throws 	KDatabaseRowException
	 */
	public function order($change)
	{
		if (!in_array('ordering', $this->_table->getColumns())) {
			throw new KDatabaseRowException("The table ".$this->_table->getTableName()." doesn't have a 'ordering' column.");
		}

		//force to integer
		settype($change, 'int');

		if($change !== 0)
		{
			$old = $this->ordering;
			$new = $this->ordering + $change;
			$new = $new <= 0 ? 1 : $new;

			$query =  'UPDATE `#__'.$this->_table->getTableName().'` ';

			if($change < 0) {
				$query .= 'SET ordering = ordering+1 WHERE '.$new.' <= ordering AND ordering < '.$old;
			} else {
				$query .= 'SET ordering = ordering-1 WHERE '.$old.' < ordering AND ordering <= '.$new;
			}

			$this->_table->getDatabase()->execute($query);

			$this->ordering = $new;
			$this->save();

			$this->_table->order();
		}

		return $this;
	}

	/**
	 * Checks out a row
	 *
	 * Requires an checked_out field to be present in the table
	 *
	 * @return 	KDatabaseRowAbstract
	 * @throws 	KDatabaseRowException
	 */
	public function checkout()
	{
		if (!in_array('checked_out', $this->_table->getColumns())) {
			throw new KDatabaseRowException("The table ".$this->_table->getTableName()." doesn't have a 'checked_out' column.");
		}

		//Get the user object
		$user = KFactory::get('lib.joomla.user')->get('id');

		//force to integer
		settype($user, 'int');

		$this->checked_out = $user->get('id');
		$this->save();

		return $this;
	}

	/**
	 * Checks in a row
	 *
	 * Requires an checked_out field to be present in the table
	 *
	 * @return 	KDatabaseRowAbstract
	 * @throws 	KDatabaseRowException
	 */
	public function checkin()
	{
		if (!in_array('checked_out', $this->_table->getColumns())) {
			throw new KDatabaseRowException("The table ".$this->_table->getTableName()." doesn't have a 'checked_out' column.");
		}

		$this->checked_out = 0;
		$this->save();

		return $this;
	}

	/**
     * Returns the column/value data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $array = $this->_data;
        $array['id'] = $this->id;

        return $array;
    }

	/**
     * Retrieve row field value
     *
     * @param  	string 	The user-specified column name.
     * @return 	string 	The corresponding column value.
     */
    public function __get($columnName)
    {
    	if($columnName == 'id') {
        	$columnName = $this->_table->getPrimaryKey();
        }
    	return $this->_data[$columnName];
    }

    /**
     * Set row field value
     *
     * @param  	string 	The column key.
     * @param  	mixed  	The value for the property.
     * @return 	void
     */
    public function __set($columnName, $value)
    {
    	if($columnName == 'id') {
        	$columnName = $this->_table->getPrimaryKey();
        }

        $this->_data[$columnName] = $value;
   }

	/**
     * Test existence of row field
     *
     * @param  string  The column key.
     * @return boolean
     */
    public function __isset($columnName)
    {
        if($columnName == 'id') {
        	$columnName = $this->_table->getPrimaryKey();
        }

    	return array_key_exists($columnName, $this->_data);
    }

    /**
     * Unset a row field
     *
     * @param	string  The column key.
     * @return	void
     */
    public function __unset($columnName)
    {
   	 	if($columnName == 'id') {
        	$columnName = $this->_table->getPrimaryKey();
        }

        unset($this->_data[$columnName]);
    }

    /**
     * Returns an associative array of object properties
     *
     * @return  array
     */
    public function getProperties()
    {
    	$result = $this->_data;
    	$result['id'] = $this->id;

        return $result;
    }

    /**
     * Set the object properties based on a named array/hash
     *
     * @param   mixed Either and associative array or another object
     * @return 	KDatabaseRowAbstract
     */
    public function setProperties( $properties )
    {
    	$properties = (array) $properties;
        $pk = $this->_table->getPrimaryKey();

        foreach ($properties as $k => $v)
        {
         	if('id' == $k) {
         		$this->_data[$pk] = $v;
         	} else {
         		$this->_data[$k] = $v;
         	}
        }

        return $this;
    }
}