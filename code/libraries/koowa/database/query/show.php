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
 * Show database query class
 *
 * @author      Gergo Erdosi <gergo@timble.net>
 * @package     Koowa_Database
 * @subpackage  Query
 */
class KDatabaseQueryShow extends KDatabaseQueryAbstract
{
    /**
     * The show clause.
     *
     * @var string
     */
    public $show;

    /**
     * The from clause.
     *
     * @var string
     */
    public $from;

    /**
     * The like clause.
     *
     * @var string
     */
    public $like;

    /**
     * The where clause.
     *
     * @var array
     */
    public $where = array();

    /**
     * Parameters to bind.
     *
     * @var array
     */
    public $params = array();

    /**
     * Build the show clause of the query.
     *
     * @param   string The name of the table.
     * @return  KDatabaseQueryShow
     */
    public function show($table) 
    {
        $this->show = $table;
        return $this;
    }

    /**
     * Build the from clause of the query.
     *
     * @param   string The name of the database or table.
     * @return  KDatabaseQueryShow
     */
    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Build the like clause of the query.
     *
     * @param   string The pattern to match.
     * @return  KDatabaseQueryShow
     */
    public function like($pattern)
    {
        $this->like = $pattern;
    
        return $this;
    }

    /**
     * Build the where clause of the query.
     *
     * @param   string The condition.
     * @param   string Combination type, defaults to 'AND'.
     * @return  KDatabaseQueryShow
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
     * Bind values to a corresponding named placeholders in the query.
     *
     * @param   array Associative array of parameters.
     * @return  KDatabaseQueryDelete
     */
    public function bind(array $params)
    {
        foreach ($params as $key => $value) {
            $this->params[$key] = $value;
        }
    
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
        $query   = 'SHOW '.$this->show;

        if($this->from)
        {
            $table  = (in_array($this->show, array('FULL COLUMNS', 'COLUMNS', 'INDEX', 'INDEXES', 'KEYS')) ? $prefix : '').$this->from;
            $query .= ' FROM '.$adapter->quoteIdentifier($table);
        }

        if($this->like)
        {
            $query .= ' LIKE '.$adapter->quoteIdentifier($this->like);
        }

        if($this->where)
        {
            $query .= ' WHERE';
            
            foreach ($this->where as $where)
            {
                if (!empty($where['combination'])) {
                    $query .= ' '.$where['combination'];
                }
            
                $query .= ' '.$adapter->quoteIdentifier($where['condition']);
            }
        }

        if($this->params)
        {
            // TODO: Use anonymous function instead of callback.
            $query = preg_replace_callback("/(?<!\w):\w+/", array($this, '_replaceParam'), $query);
        }

        return $query;
    }
    
    /**
     * Callback method for parameter replacement.
     * 
     * @param  array  $matches Matches of preg_replace_callback.
     * @return string The replacement string.
     */
    protected function _replaceParam($matches)
    {
        $key    = substr($matches[0], 1);
        $prefix = '';
        
        if(in_array($this->show, array('FULL TABLES', 'OPEN TABLES', 'TABLE STATUS', 'TABLES')) &&
            ($this->like && $key == 'like' || $this->where && ($key == 'name' || $key == 'table')))
        {
            $prefix = $this->getAdapter()->getTablePrefix();
        }
        
        $replacement = $this->getAdapter()->quoteValue($prefix.$this->params[$key]);
        
        return is_array($this->params[$key]) ? '('.$replacement.')' : $replacement;
    }
}
