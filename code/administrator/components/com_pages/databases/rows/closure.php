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
 * Closure Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseRowClosure extends KDatabaseRowDefault
{
    /**
     * Returns the siblings of the row
     *
     * @return KDatabaseRowAbstract
     */
    public function getSiblings()
    {
        if($this->id)
        {
            $table = $this->getTable();
            $query = $this->getService('koowa:database.query.select')
                ->columns('tbl.*')
                ->columns(array('level' => 'COUNT(crumbs.ancestor_id)'))
                ->columns(array('path' => 'GROUP_CONCAT(crumbs.ancestor_id ORDER BY crumbs.level DESC SEPARATOR \'/\')'))
                ->table(array('tbl' => $table->getName()))

                ->join(array('crumbs' => $table->getRelationTable()), 'crumbs.descendant_id = tbl.'.$table->getIdentityColumn(), 'INNER')

                ->where('tbl.'.$table->getIdentityColumn().' <> :id')
                ->group('tbl.'.$table->getIdentityColumn())
                ->having('level = :level')
                ->order('path', 'ASC')
                ->bind(array('id' => $this->id, 'level' => $this->level));

            if(count($this->parent_ids))
            {
                $query->join(array('relations' => $table->getRelationTable()), 'relations.descendant_id = tbl.'.$table->getIdentityColumn(), 'INNER')
                    ->where('relations.ancestor_id = :parent_id')
                    ->bind(array('parent_id' => end(array_values($this->parent_ids))));
            }

            $result = $this->getTable()->select($query, KDatabase::FETCH_ROWSET);
        }
        else $result = null;

        return $result;
    }

    /**
     * Returns the first ancestor of the row
     *
     * @return KDatabaseRowAbstract|null Parent row or null if there is no parent
     */
    public function getParent()
    {
        return $this->getAncestors(1);
    }

    /**
     * Get ancestors of the row
     *
     * @param int $level Filters results by level
     * @return KDatabaseRowsetAbstract A rowset containing all ancestors
     */
    public function getAncestors($level = null)
    {
        if($this->id && $this->parent_ids)
        {
            $table = $this->getTable();
            $query = $this->getService('koowa:database.query.select')
                ->columns('tbl.*')
                ->columns(array('level' => 'COUNT(crumbs.ancestor_id)'))
                ->columns(array('path' => 'GROUP_CONCAT(crumbs.ancestor_id ORDER BY crumbs.level DESC SEPARATOR \'/\')'))
                ->table(array('tbl' => $table->getName()))
                ->join(array('crumbs' => $table->getRelationTable()), 'crumbs.descendant_id = tbl.'.$table->getIdentityColumn(), 'INNER')
                ->where('tbl.'.$table->getIdentityColumn().' IN :parent_ids')
                ->group('tbl.'.$table->getIdentityColumn())
                ->order('path', 'ASC');

            $ids = $level ? array_slice($this->parent_ids, $level < count($this->parent_ids) ? count($this->parent_ids) - $level : 0) : $this->parent_ids;
            $query->bind(array('parent_ids' => $ids));

            $result = $this->getTable()->select($query, KDatabase::FETCH_ROWSET);
        }
        else $result = null;

        return $result;
    }

    /**
     * Get descendants of the row
     *
     * @param int $level Filters results by level
     *
     * @return KDatabaseRowsetAbstract A rowset containing all descendants
     */
    public function getDescendants($level = null)
    {
        if($this->id)
        {
            $table = $this->getTable();
            $query = $this->getService('koowa:database.query.select')
                ->columns('tbl.*')
                ->columns(array('level' => 'COUNT(crumbs.ancestor_id)'))
                ->columns(array('path' => 'GROUP_CONCAT(crumbs.ancestor_id ORDER BY crumbs.level DESC SEPARATOR \'/\')'))
                ->table(array('tbl' => $table->getName()))
                ->join(array('relations' => $table->getRelationTable()), 'relations.descendant_id = tbl.'.$table->getIdentityColumn(), 'INNER')
                ->join(array('crumbs' => $table->getRelationTable()), 'crumbs.descendant_id = tbl.'.$table->getIdentityColumn(), 'INNER')
                ->where('relations.ancestor_id = :id')
                ->where('tbl.'.$table->getIdentityColumn().' <> :id')
                ->group('tbl.'.$table->getIdentityColumn())
                ->order('path', 'ASC')
                ->bind(array('id' => $this->id));

            if($level) {
                $query->having('level = :level')->bind(array('level' => $this->level + $level));
            }

            $result = $this->getTable()->select($query, KDatabase::FETCH_ROWSET);
        }
        else $result = null;

        return $result;
    }

    /**
     * Checks if the given row is a descendant of this one
     *
     * @param int|object $target Either an integer or an object with id property
     * @return boolean
     */
    public function isDescendantOf($target)
    {
        return in_array($target->id, $this->parent_ids);
    }

    /**
     * Checks if the given row is an ancestor of this one
     *
     * @param int|object $target Either an integer or an object with id property
     * @return boolean
     */
    public function isAncestorOf($target)
    {
        $table = $this->getTable();
        $query = $this->getService('koowa:database.query.select')
            ->columns('COUNT(*)')
            ->table($table->getRelationTable())
            ->where('descendant_id = :descendant_id')
            ->where('ancestor_id = :ancestor_id')
            ->bind(array('descendant_id' => $this->id, 'ancestor_id' => $target));

        return (bool) $table->select($query, KDatabase::FETCH_FIELD);
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'parent_id':
            {
                if(!isset($this->_data['parent_id'])) {
                    return end(array_values($this->parent_ids));
                }

            } break;
                
            case 'parent_ids':
            {
                $ids = array_map('intval', explode('/', $this->path));
                array_pop($ids);
                return $ids;

            } break;
                
            case 'parent_path':
            {
                return substr($this->path, 0, strrpos($this->path, '/'));

            } break;
        }

        return parent::__get($name);
    }
}
