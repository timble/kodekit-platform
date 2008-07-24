<?php
/**
 * @version		$Id$
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	(C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Row Class
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa_Database
 * @subpackage  Row
 * @uses 		KPatternClass
 */
abstract class KDatabaseRowAbstract extends KObject
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
    protected $_tableClass;

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
        $this->mixin(new KPatternClass($this, 'Row'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Set table object and class name
		$this->_tableClass  = ucfirst($this->getClassName('prefix')).'Table'.ucfirst($this->getClassName('suffix'));
		$this->_table       = isset($options['table']) ? $options['table'] : KFactory::get($this->_tableClass);

		// Set data
		if(isset($options['data']))  {
			$this->_data = $options['data'];
		}
        else
        {
        	// Set defaults
            $this->reset();
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
    protected function _initialize($options)
    {
        $defaults = array(
            'base_path' => null,
            'name'      => array(
                        'prefix'    => 'k',
                        'base'      => 'row',
                        'suffix'    => 'default'
                        ),
            'table'     => null
        );

        return array_merge($defaults, $options);
    }

    /**
     * Returns the row's id
     */
    public function getId()
    {
    	return $this->{$this->_table->getPrimaryKey()};
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
     * Saves the properties to the database.
     *
     * This performs an intelligent insert/update, and reloads the
     * properties with fresh data from the table on success.
     *
     * Can be overloaded/supplemented by the child class
     *
     * @return mixed The primary key value(s), as an associative array if the
     *     			 key is compound, or a scalar if the key is single-column.
     */
    public function save()
    {
        $key = $this->_table->getPrimaryKey();

        if($this->$key)
        {
            $where = KDatabaseQuery::getInstance()->where($key, '=', $this->$key);
            $this->_table->update($this->getProperties(), $where);
        }
        else $this->_table->insert($this->getProperties());

        if($err = $this->_table->getError()) {
            $this->setError($this->getClassName('all').'::save() failed: '.$err);
        }

        return $this;
    }

	/**
     * Deletes existing rows.
     *
     * @return int The number of rows deleted.
     */
    public function delete()
    {
		$result = 0;
        return $result;
    }

	/**
     * Resets to the default properties
     *
     * @return  this
     */
    public function reset()
    {
        $this->_data = $this->_table->getDefaults();
        return $this;
    }

	/**
     * Returns the column/value data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }

	/**
     * Retrieve row field value
     *
     * @param  string $columnName The user-specified column name.
     * @return string             The corresponding column value.
     * @throws KDatabaseRowException if the $columnName is not a column in the row.
     */
    public function __get($columnName)
    {
        if (!array_key_exists($columnName, $this->_data)) {
            throw new KDatabaseRowException('Specified column '.$columnName.' is not in the row');
        }
        return $this->_data[$columnName];
    }

    /**
     * Set row field value
     *
     * @param  string $columnName The column key.
     * @param  mixed  $value      The value for the property.
     * @return void
     * @throws KDatabaseRowException
     */
    public function __set($columnName, $value)
    {
      	if (!array_key_exists($columnName, $this->_data)) {
            throw new KDatabaseRowException('Specified column '.$columnName.' is not in the row');
        }
        $this->_data[$columnName] = $value;
		// TODO implement this?
		//$this->_modifiedFields[$columnName] = true;
   }

	/**
     * Test existence of row field
     *
     * @param  string  $columnName   The column key.
     * @return boolean
     */
    public function __isset($columnName)
    {
        return array_key_exists($columnName, $this->_data);
    }

    /**
     * Returns an associative array of object properties
     *
     * @return  array
     */
    public function getProperties()
    {
        return $this->_data;
    }


    /**
    * Set the object properties based on a named array/hash
    *
    * @param    $array  mixed Either and associative array or another object
    * @return   this
    */
    public function setProperties( $properties )
    {
        $properties = (array) $properties;

        foreach ($this->_data as $k => $v) {
            if(isset($properties[$k])) {
                $this->_data[$k] = $properties[$k];
            }
        }

        return $this;
    }

}