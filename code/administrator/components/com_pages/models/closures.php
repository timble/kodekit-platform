<?php
/**
 * @version     $Id: pages.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Closures Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesModelClosures extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->remove('sort')->insert('sort', 'cmd', 'path')
            ->insert('parent_id', 'int')
            ->insert('level', 'int');
    }

    /**
     * Specialized to NOT use a count query since all the inner joins get confused over it
     *
     * @see KModelTable::getTotal()
     */
    public function getTotal()
    {
        // Get the data if it doesn't already exist
        if(!isset($this->_total))
        {
            if($this->isConnected())
            {
                $query = $this->getService('koowa:database.query.select');
    
                $this->_buildQueryColumns($query);
                $this->_buildQueryTable($query);
                $this->_buildQueryJoins($query);
                $this->_buildQueryWhere($query);
                $this->_buildQueryGroup($query);
                $this->_buildQueryHaving($query);

                $total = count($this->getTable()->select($query, KDatabase::FETCH_FIELD_LIST));
                $this->_total = $total;
            }
        }
    
        return $this->_total;
    }
    
    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array('level' => 'COUNT(crumbs.ancestor_id)'))
            ->columns(array('path' => 'GROUP_CONCAT(crumbs.ancestor_id ORDER BY crumbs.level DESC SEPARATOR \'/\')'));
    }
    
    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);
        $state = $this->getState();

        $relation  = $this->getTable()->getRelationTable();
        $id_column = $this->getTable()->getIdentityColumn();

        $query->join(array('crumbs' => $relation), 'crumbs.descendant_id = tbl.'.$id_column, 'INNER');
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if($state->parent_id)
        {
            $id_column = $this->getTable()->getIdentityColumn();

            $query->where('crumbs.ancestor_id IN :parent_id')
                ->where('tbl.'.$id_column.' NOT IN :parent_id')
                ->bind(array('parent_id' => $state->parent_id));

            if($state->level !== null) {
                $query->where('crumbs.level IN :level')->bind(array('level' => (array) $state->level));
            }
        }
    }

    protected function _buildQueryGroup(KDatabaseQuerySelect $query)
    {
        $query->group('tbl.'.$this->getTable()->getIdentityColumn());

        parent::_buildQueryGroup($query);
    }

    protected function _buildQueryHaving(KDatabaseQuerySelect $query)
    {
        // If we have a parent id level is set using the where clause
        $state = $this->getState();
        if(!$state->parent_id && $state->level !== null) {
            $query->having('level IN :level');
        }

        parent::_buildQueryHaving($query);
    }

    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
        $query->order('path', 'ASC');
    }
}
