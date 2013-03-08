<?php
/**
 * @package      Nooku_Server
 * @subpackage   Categories
 * @copyright    Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Orderable Database Behavior Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @package     Nooku_Server
 * @subpackage  Categories    
 */
class ComCategoriesDatabaseBehaviorOrderable extends Framework\DatabaseBehaviorOrderable
{
    protected $_table;

    protected $_parent;

    /**
     * The parent column name
     *
     * @var string
     */
    protected $_parent_column;

    public function __construct( Framework\Config $config)
    {
        $config->append(array('parent_column' => null));
        $this->_parent_column = $config->parent_column;
        
        parent::__construct($config);
    }

    public function _buildQueryWhere($query)
    {
        parent::_buildQueryWhere($query);
        
        if($this->_parent_column)
        {
            $parent = $this->_parent ? $this->_parent : $this->{$this->_parent_column};   
            $query->where($this->_table->mapColumns($this->_parent_column).' = :parent')->bind(array('parent' => $parent));
        }

        $query->where('table = :table')->bind(array('table' => $this->table));
    }
    
    /**
     * Changes the rows ordering if the virtual order field is set. Order is
     * relative to the row's current position. Order is to be only set if section 
     * unchanged.
     * Inserts space in order sequence of new section if section changed.
     *
     * @param   Framework\CommandContext Context
     */
    protected function _beforeTableUpdate(Framework\CommandContext $context)
    {
        $this->_table = $context->getSubject();
        if(isset($this->ordering))
        {
            if (isset($this->order))
            {
                unset($this->old_parent);        
                $this->order($this->order);
            } 
            elseif (isset($this->old_parent)) 
            {
                $max = $this->getMaxOrdering();
                
                if ($this->ordering <= 0) {
                    $this->ordering = $max + 1;
                } elseif ($this->ordering <= $max ) {
                    $this->reorder($this->ordering);
                }                
            }
        }
    }

    /**
     * Reorders the old section if record has changed sections
     *
     * @param   Framework\CommandContext Context
     */
    protected function _afterTableUpdate(Framework\CommandContext $context)
    {
        $this->_table = $context->getSubject();
        if (isset($this->old_parent) && $this->old_parent != $this->{$this->_parent_column} )
        {
            $this->_parent = $this->old_parent;
            $this->reorder();
        }
    }
    

    /**
     * Modify the select query
     *
     * If the $this->_parent_column is set, this will modify the query to add the column needed by the behavior
     */
    protected function _beforeTableSelect(Framework\CommandContext $context)
    {
        $this->_table = $context->getSubject();
        if($parent_column = $this->_parent_column)
        {
            $query = $context->query;
            if(!is_null($query) && !$query->isCountQuery())
            {
                $table = $context->getSubject();
                $parent_column = $table->mapColumns($parent_column);

                $subquery = $this->getService('lib://nooku/database.query.select')
                                 ->columns(array($parent_column, 'order_total' => 'COUNT(ordering)'))
                                 ->table($table->getBase())
                                 ->group($parent_column);
                
                $query->columns('orderable.order_total')
                      ->join(array('orderable' => $subquery), 'orderable.'.$parent_column.' = tbl.'.$parent_column);
            }
        }
    }  
}