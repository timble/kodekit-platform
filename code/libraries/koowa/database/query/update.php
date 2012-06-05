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
 * Update database query class
 *
 * @author      Gergo Erdosi <gergo@timble.net>
 * @package     Koowa_Database
 * @subpackage  Query
 */
class KDatabaseQueryUpdate extends KDatabaseQueryAbstract
{
    /**
     * The table name.
     *
     * @var string
     */
    public $table;

    /**
     * Data of the set clause.
     *
     * @var array
     */
    public $values = array();

    /**
     * Data of the where clause.
     *
     * @var array
     */
    public $where = array();

    /**
     * Data of the order clause.
     *
     * @var array
     */
    public $order = array();

    /**
     * The number of rows that can be updated.
     *
     * @var integer
     */
    public $limit;

    /**
     * Build the table clause 
     *
     * @param   string The name of the table to update.
     * @return  KDatabaseQueryUpdate
     */
    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Build the set clause 
     *
     * @param   array|string $columns An array or string of columns to update.
     * @return  \KDatabaseQueryUpdate
     */
    public function value($values)
    {
        $this->values = array_merge($this->values, (array) $values);

        return $this;
    }

    /**
     * Build the where clause
     *
     * @param   string $condiaiton  The condition.
     * @param   string $combination Combination type, defaults to 'AND'.
     * @return  \KDatabaseQueryUpdate
     */
    public function where($condition, $combination = 'AND')
    {
        $this->where[] = array(
            'condition'   => $condition,
            'combination' => count($this->where) ? $combination : ''
        );

        return $this;
    }

    /**
     * Build the order clause
     *
     * @param   array|string $columns   A string or array of ordering columns.
     * @param   string       $direction Either DESC or ASC.
     * @return  \KDatabaseQueryUpdate
     */
    public function order($columns, $direction = 'ASC')
    {
        foreach ((array) $columns as $column) {
            $this->order[] = array(
                'column'    => $column,
                'direction' => $direction
            );
        }

        return $this;
    }

    /**
     * Build the limit clause
     *
     * @param   integer $limit Number of items to update.
     * @return  \KDatabaseQueryUpdate
     */
    public function limit($limit)
    {
        $this->limit  = (int) $limit;
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
        $prefix  = $adapter->getTablePrefix();
        $query   = 'UPDATE';

        if($this->table) {
            $query .= ' '.$adapter->quoteIdentifier($prefix.$this->table);
        }

        if($this->values)
        {
            $values = array();
            foreach($this->values as $value) {
                $values[] = ' '. $adapter->quoteIdentifier($value);
            }

            $query .= ' SET '.implode(', ', $values);
        }

        if($this->where)
        {
            $query .= ' WHERE';

            foreach($this->where as $where) 
            {
                if(!empty($where['combination'])) {
                    $query .= ' '.$where['combination'];
                }

                $query .= ' '.$adapter->quoteIdentifier($where['condition']);
            }
        }

        if($this->order)
        {
            $query .= ' ORDER BY ';

            $list = array();
            foreach($this->order as $order) {
                $list[] = $adapter->quoteIdentifier($order['column']).' '.$order['direction'];
            }

            $query .= implode(' , ', $list);
        }

        if($this->limit) {
            $query .= ' LIMIT '.$this->offset.' , '.$this->limit;
        }

        if($this->params) {
            $query = $this->_replaceParams($query);
        }

        return $query;
    }
}