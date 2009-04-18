<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 * @copyright	(C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Database Row Class
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Row
 * @uses 		KMixinClass
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
    public function __construct(array $options = array())
    {
        // Initialize the options
        $options  = $this->_initialize($options);

        // Mixin the KMixinClass
        $this->mixin(new KMixinClass($this, 'Row'));

        // Assign the classname with values from the config
        $this->setClassName($options['name']);

		// Set table object and class name
		$this->_tableClass  = 'com.'.$this->getClassName('prefix').'.table.'.$this->getClassName('suffix');
		$this->_table       = isset($options['table']) ? $options['table'] : KFactory::get($this->_tableClass);
		
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
     * @throws KDatabaseRowException
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
        	
        if($this->_data[$key])
        {
        	$this->_table->update($properties, $this->_data[$key]);
        }
        else 
        {
        	if($this->_table->insert($properties)) {
        		$this->id = $this->_table->getDBO()->insertid();
        	}
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
     * Increase hit counter by 1
     *
     * @return this
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
	 * @param	int	Amount to move up or down
	 * @return 	KDatabaseRowAbstract
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
			
			$this->_table->getDBO()->execute($query);

			$this->ordering = $new;
			$this->save();
		
			$this->_table->reorder();
		}
		
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
     * @param  string $columnName The user-specified column name.
     * @return string             The corresponding column value.
     * @throws KDatabaseRowException if the $columnName is not a column in the row.
     */
    public function __get($columnName)
    {
        $data = null;
           
    	if($columnName == 'id') {
        	$data = $this->_data[$this->_table->getPrimaryKey()];
        } else {
        	$data = $this->_data[$columnName];
        }
      
    	return $data;
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
    	if($columnName == 'id') {
        	$this->_data[$this->_table->getPrimaryKey()] = $value;
        } else {
        	$this->_data[$columnName] = $value;
        }
   }

	/**
     * Test existence of row field
     *
     * @param  string  $columnName   The column key.
     * @return boolean
     */
    public function __isset($columnName)
    {
        if($columnName == 'id') {
        	$columnName = $this->_data[$this->_table->getPrimaryKey()];
        }
    	
    	return array_key_exists($columnName, $this->_data);
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
    * @param    $array  mixed Either and associative array or another object
    * @return   this
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