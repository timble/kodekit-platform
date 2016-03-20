<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-categories for the canonical source repository
 */

namespace Kodekit\Component\Categories;

use Kodekit\Library;

/**
 * Orderable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Categories
 */
class DatabaseBehaviorOrderable extends Library\DatabaseBehaviorOrderable
{
    protected $_parent;

    /**
     * The parent column name
     *
     * @var string
     */
    protected $_parent_column;

    public function __construct( Library\ObjectConfig $config)
    {
        $this->_parent_column = $config->parent_column;

        parent::__construct($config);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'parent_column' => 'parent_id'
        ));

        parent::_initialize($config);
    }

    /**
     * Check if the behavior is supported
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $row = $this->getMixer();

        if($row instanceof Library\DatabaseRowInterface)
        {
            if($row->hasProperty($this->_parent_column))  {
                return true;
            }
        }

        return parent::isSupported();
    }

    public function _buildQueryWhere($query)
    {
        parent::_buildQueryWhere($query);

        $parent = $this->_parent ? $this->_parent : $this->{$this->_parent_column};

        $query->where($this->getTable()->mapColumns($this->_parent_column).' = :parent')->bind(array('parent' => $parent));
        $query->where('table = :table')->bind(array('table' => $this->table));
    }

    /**
     * Changes the rows ordering if the virtual order field is set.
     *
     * Order is relative to the row's current position. Order is to be only set if category unchanged.
     * Inserts space in order sequence of new category if category changed.
     *
     * @param   Library\DatabaseContext $context
     */
    protected function _beforeUpdate(Library\DatabaseContext $context)
    {
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
     * Reorders the old category if record has changed categories
     *
     * @param   Library\DatabaseContext $context
     */
    protected function _afterUpdate(Library\DatabaseContext $context)
    {
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
    protected function _beforeSelect(Library\DatabaseContext $context)
    {
        if($parent_column = $this->_parent_column)
        {
            $query = $context->query;
            if(!is_null($query) && !$query->isCountQuery())
            {
                $table = $context->getSubject();
                $parent_column = $table->mapColumns($parent_column);

                $subquery = $this->getObject('lib:database.query.select')
                                 ->columns(array($parent_column, 'order_total' => 'COUNT(ordering)'))
                                 ->table($table->getBase())
                                 ->group($parent_column);

                $query->columns('orderable.order_total')
                      ->join(array('orderable' => $subquery), 'orderable.'.$parent_column.' = tbl.'.$parent_column);
            }
        }
    }
}