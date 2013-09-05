<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Show Database Query
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Library\Database
 */
class DatabaseQueryShow extends DatabaseQueryAbstract
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
     * Build the show clause 
     *
     * @param   string $table The name of the table.
     * @return  DatabaseQueryShow
     */
    public function show($table) 
    {
        $this->show = $table;
        return $this;
    }

    /**
     * Build the from clause 
     *
     * @param   string $form The name of the database or table.
     * @return  DatabaseQueryShow
     */
    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Build the like clause 
     *
     * @param   string $pattern The pattern to match.
     * @return  DatabaseQueryShow
     */
    public function like($pattern)
    {
        $this->like = $pattern;
    
        return $this;
    }

    /**
     * Build the where clause
     *
     * @param   string $condition   The condition.
     * @param   string $combination Combination type, defaults to 'AND'.
     * @return  DatabaseQueryShow
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
     * Render the query to a string.
     *
     * @return  string  The query string.
     */
    public function __toString()
    {
        $adapter = $this->getAdapter();
        $query   = 'SHOW '.$this->show;

        if($this->from)
        {
            $table  = $this->from;
            $query .= ' FROM '.$adapter->quoteIdentifier($table);
        }

        if($this->like) {
            $query .= ' LIKE '.$adapter->quoteIdentifier($this->like);
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

        if($this->_parameters) {
            $query = $this->_replaceParameters($query);
        }

        return $query;
    }
    
    /**
     * Callback method for parameter replacement.
     * 
     * @param  array  $matches Matches of preg_replace_callback.
     * @return string The replacement string.
     */
    protected function _replaceParamsCallback($matches)
    {
        $key         = substr($matches[0], 1);
        $replacement = $this->getAdapter()->quoteValue($this->_parameters[$key]);
        
        return is_array($this->_parameters[$key]) ? '('.$replacement.')' : $replacement;
    }
}
