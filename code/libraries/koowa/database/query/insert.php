<?php
/**
 * @version     $Id$
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Insert database query class
 *
 * @author      Gergo Erdosi <gergo@timble.net>
 * @package     Koowa_Database
 * @subpackage  Query
 */
class KDatabaseQueryInsert extends KDatabaseQueryAbstract
{
    /**
     * The table name.
     *
     * @var string
     */
    public $table;

    /**
     * Array of column names.
     *
     * @var array
     */
    public $columns = array();

    /**
     * Array of values.
     *
     * @var array
     */
    public $values = array();

    /**
     * Build the table clause of the query.
     *
     * @param   string The table name.
     * @return  KDatabaseQueryInsert
     */
    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Build the columns clause of the query.
     *
     * @param   array Array of column names.
     * @return  KDatabaseQueryUpdate
     */
    public function columns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Build the values clause of the query.
     *
     * @param   array Array of values.
     * @return  KDatabaseQueryUpdate
     */
    public function values($values)
    {
        if(!$values instanceof KDatabaseQuerySelect)
        {
            if (!$this->columns && !is_numeric(key($values))) {
                $this->columns(array_keys($values));
            }

            $this->values[] = array_values($values);
        }
        else $this->values = $values;

        return $this;
    }

    /**
     * Render the query to a string.
     *
     * @return  string  The query string.
     */
    public function __toString()
    {
        $adapter = $this->getAdapter();
        $prefix = $adapter->getTablePrefix();
        $query = 'INSERT';

        if($this->table) {
            $query .= ' INTO '.$adapter->quoteIdentifier($prefix.$this->table);
        }

        if($this->columns) {
            $query .= '('.implode(', ', array_map(array($adapter, 'quoteIdentifier'), $this->columns)).')';
        }

        if($this->values)
        {
            if(!$this->values instanceof KDatabaseQuerySelect)
            {
                $query .= ' VALUES'.PHP_EOL;

                $values = array();
                foreach ($this->values as $value) {
                    $values[] = '('.implode(', ', array_map(array($adapter, 'quoteValue'), $value)).')';
                }

                $query .= implode(', '.PHP_EOL, $values);
            }
            else $query .= ' '.$this->values;
        }

        return $query;
    }
}
