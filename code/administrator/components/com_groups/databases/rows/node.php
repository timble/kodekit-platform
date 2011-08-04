<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Node Database Row Class
 *
 * @author      Israel Canasa <israel@timble.net>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 */
class ComGroupsDatabaseRowNode extends KDatabaseRowDefault
{
    /**
     * Column name for left
     *
     * @var string
     */
    protected $_left_column;
    
    /**
     * Column name for right
     *
     * @var string
     */
    protected $_right_column;
    
    /**
     * Column name for parent
     *
     * @var string
     */
    protected $_parent_column;
    
    /**
     * Column name for level. Level is located in the view and not on base table.
     *
     * @var string
     */
    protected $_level_column;
    
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        if (isset($config->left_column)) {
            $this->_left_column = $config->left_column;
        }
        
        if (isset($config->right_column)) {
            $this->_right_column = $config->right_column;
        }
        
        if (isset($config->level_column)) {
            $this->_level_column = $config->level_column;
        }
        
        if (isset($config->parent_column)) {
            $this->_parent_column = $config->parent_column;
        }
    }
    
    /**
     * Puts the node into the tree before saving the row. 
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    public function save()
    {
        // If there is no instruction to move the node, proceed to saving as usual
        if (!isset($this->_data['tree_location'])) {

            return parent::save();
        }

        $target = $this->getTable()->select($this->_data['target_id'], KDatabase::FETCH_ROW);
        $parent = null;
        
        // Lock the table
        //$this->getTable()->lock();
        
        // This switch statement is for avoiding repeating code in the next switch statements
        switch ($this->_data['tree_location']) 
        {
            case 'before':
            case 'after':
                // Don't allow ROOT to have siblings
                if (!$target->level)
                {
                    throw new KDatabaseRowException('ROOT can not have siblings');
                }
                $parent = $target->parent;
            break;
            case 'firstchild':
            case 'lastchild':
                $parent = $target->id;
            break;
        }
        
        if ($this->_new) 
        {
            switch ($this->_data['tree_location']) 
            {
                case 'before':
                    $this->_insertBeforeSibling($target);
                break;

                case 'after':
                    $this->_insertAfterSibling($target);
                break;
                
                case 'firstchild':
                    $this->_insertAsFirstChild($target);
                break;

                case 'lastchild':
                    $this->_insertAsLastChild($target);
                break;
            }
        }
        else
        {
            // Check the instruction from tree_location where the node should be moved
            switch ($this->_data['tree_location']) 
            {
                case 'before':
                    $this->_moveBeforeSibling($target);
                break;

                case 'after':
                    $this->_moveAfterSibling($target);
                break;
                
                case 'firstchild':
                    $this->_moveAsFirstChild($target);
                break;

                case 'lastchild':
                    $this->_moveAsLastChild($target);
                break;
            }
        }
        // Change the parent only if tree location was specified
        if (!is_null($parent)) {
            $this->parent = $parent;
        }
        
        // Unset tree_location after moving or inserting. This avoids problems with sluggable behavior.
        unset($this->_data['tree_location']);
        
        $result = parent::save();
        //$this->getTable()->unlock();
        
        return $result;
    }
    
    /**
     * Deletes a node or subtree and all its children
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    public function delete()
    {
        // Delete the category and all its children.
        $query  = 'DELETE FROM `'.$this->getTable()->getPrefixedBase().'` 
            WHERE `'.$this->_left_column.'` >= '.$this->left.
            ' AND `'.$this->_right_column.'` <= '.$this->right;
        $this->getTable()->getDatabase()->execute($query);
        
        $this->_deleteSpace($this->left, $this->_getSize());
        
        return true;
    }
    
    /**
     * Returns the descendants of the current node depending on the parameters, 
     * it can return direct children or all descendants
     *
     * TODO: Getting Descendants should probably not be here since this is incompatible with Table/Row Data Gateway pattern?
     *
     * @access public
     * @param string $direction direction to order the left column by.
     * @param bool  $direct_children_only
     * @param int|bool $limit
     * @return KDatabaseRowsetAbstract
     */
    public function getDescendants($direction = 'ASC', $direct_children_only = false, $limit = false)
    {
        $query = $this->getTable()->getDatabase()->getQuery();
        
        $query = $query->where($this->_left_column, '>', $this->left)
                ->where($this->_right_column, '<', $this->right)
                ->order($this->_left_column, $direction);

        if ($direct_children_only)
        {
            $query->where($this->_level_column, '=', $this->level + 1);
        }

        if ($limit)
        {
            $query->limit($limit);
        }
        
        // If we don't reset the rowset, the table will just add the data to its rowset. So here we flush the rowset.
        $this->getTable()->getRowset()->reset();
        
        return $this->getTable()->select($query, KDatabase::FETCH_ROWSET);
    }
    
    /**
     * Returns the immediate children of the current node.
     *
     * @access public
     * @param string $direction direction to order the left column by.
     * @param int|bool $limit
     * @return KDatabaseRowsetAbstract
     */
    public function getChildren($direction = 'ASC', $limit = false)
    {
        return $this->getDescendants($direction, true, $limit);
    }
    
    /**
     * Returns the parents of the current node.
     *
     * @access public
     * @param string $direction direction to order the left column by.
     * @return KDatabaseRowsetAbstract
     */
    public function getParents($direction = 'ASC')
    {
        $query = $this->getTable()->getDatabase()->getQuery()
            //->where($this->_level_column, '<>', 0)
            ->where($this->_left_column, '<=', $this->left)
            ->where($this->_right_column, '>=', $this->right)
            ->where($this->getTable()->getIdentityColumn(), '<>', $this->id)
            ->order($this->_left_column, $direction);
        
        // If we don't reset the rowset, the table will just add the data to its rowset. So here we flush the rowset.
        $this->getTable()->getRowset()->reset();
        
        return $this->getTable()->select($query, KDatabase::FETCH_ROWSET);
    }
    
    /**
     * Check if the current node is a descendant of the target
     *
     * @return boolean  
     */
    public function isDescendantOf($target)
    {
        return (
            $this->left > $target->left 
            AND $this->right < $target->right 
        );
    }

    /**
     * Inserts the current node as the first child of the target
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _insertAsFirstChild($target)
    {
        return $this->_insert($target, 'left', 1);
    }
    
    /**
     * Inserts the current node as the last child of the target
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _insertAsLastChild($target)
    {
        return $this->_insert($target, 'right', 0);
    }

    /**
     * Inserts the current node as the previous sibling of the target
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _insertAfterSibling($target)
    {
        return $this->_insert($target, 'right', 1);
    }

    /**
     * Inserts the current node as the next sibling of the target
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _insertBeforeSibling($target)
    {   
        return $this->_insert($target, 'left', 0);
    }
    
    /**
     * Moves the current node as the previous sibling of the target
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _moveBeforeSibling($target)
    {   
        return $this->_move($target, true, 0);
    }
    
    /**
     * Moves the current node as the next sibling of the target
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _moveAfterSibling($target)
    {
        return $this->_move($target, false, 1);
    }
    
    /**
     * Moves the current node as the first child of the target
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _moveAsFirstChild($target)
    {
        return $this->_move($target, true, 1);
    }
    
    /**
     * Moves the current node as the last child of the target
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _moveAsLastChild($target)
    {   
        return $this->_move($target, false, 0);
    }
    
    /**
     * Creates the needed space before moving or inserting nodes
     */
    protected function _createSpace($start, $size = 2)
    {
        // Update lft value of existing categories.
        $query  = 'UPDATE `'.$this->getTable()->getPrefixedBase().'` SET `'.$this->_left_column.'` = `'.$this->_left_column.'` + '.$size.
            ' WHERE `'.$this->_left_column.'` >= '.$start;

        $this->getTable()->getDatabase()->execute($query);

        // Update rgt value of existing categories.
        $query  = 'UPDATE `'.$this->getTable()->getPrefixedBase().'` SET `'.$this->_right_column.'` = `'.$this->_right_column.'` + '.$size.
            ' WHERE `'.$this->_right_column.'` >= '.$start;

        $this->getTable()->getDatabase()->execute($query);
    }
    
    /**
     * Deletes unnecessary space resulting from moving nodes
     */
    protected function _deleteSpace($start, $size = 2)
    {

        // Update lft value of existing categories.
        $query  = 'UPDATE `'.$this->getTable()->getPrefixedBase().'` SET `'.$this->_left_column.'` = `'.$this->_left_column.'` - '.$size.
            ' WHERE `'.$this->_left_column.'` >= '.$start;

        $this->getTable()->getDatabase()->execute($query);

        // Update rgt value of existing categories.
        $query  = 'UPDATE `'.$this->getTable()->getPrefixedBase().'` SET `'.$this->_right_column.'` = `'.$this->_right_column.'` - '.$size.
            ' WHERE `'.$this->_right_column.'` >= '.$start;

        $this->getTable()->getDatabase()->execute($query);
    }
    
    /**
     * Inserts nodes according to parameters
     *      By manipulating these parameters, nodes can be inserted as child or sibling of the target node
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _insert($target, $copy_left_from, $left_offset)
    {
        // Don't insert if it's not new
        if (!$this->isNew())
            return false;

        $this->left  = $target->{$copy_left_from} + $left_offset;
        $this->right = $this->left + 1;
        
        $this->_createSpace($this->left);
        
        return true;
    }
    
    /**
     * Moves nodes according to parameters
     *      By manipulating these parameters, nodes can be moved as child or sibling of the target node
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    protected function _move($target, $left_column, $left_offset)
    {   
        // Stop $this being moved into a descendant or itself or disallow if target is root
        if ($target->isDescendantOf($this) || $this->id === $target->id)
        {
            return false;
        }
        
        // Calculate left_offset according to the parameters and current positioning of the target
        $left_offset = ($left_column === TRUE ? $target->left : $target->right) + $left_offset;

        // Calculate the size of the space needed
        $size = $this->_getSize();
    
        $this->_createSpace($left_offset, $size);

        
        // Reload the data from the database
        $current_row = $this->getTable()->select(array('id' => $this->id), KDatabase::FETCH_ROW);
        
        // Copy the new data created by _createSpace but don't mark it as modified
        $this->setData(array(
            $this->_left_column => $current_row->left,
            $this->_right_column => $current_row->right,
        ), false);
        
        $offset = ($left_offset - $this->left);
        
        // Adjust the left and right values according to the calculated offsets.
        $query = 'UPDATE `'.$this->getTable()->getPrefixedBase().'` 
            SET `'.$this->_left_column.'` = `'.$this->_left_column.'` + '.$offset.'
            , `'.$this->_right_column.'` = `'.$this->_right_column.'` + '.$offset.'
            WHERE `'.$this->_left_column.'` >= '.$this->left.'
            AND `'.$this->_right_column.'` <= '.$this->right;

        $this->getTable()->getDatabase()->execute($query);
    
        // Delete the leftover space 
        $this->_deleteSpace($this->left, $size);
        
        return true;
    }
    
    /**
     * Get the size needed for creating space
     *
     * @return integer  
     */
    protected function _getSize()
    {
        return ($this->right - $this->left) + 1;
    }
    
    public function __get($column)
    {
        switch ($column) 
        {
            case 'left':
                return parent::__get($this->_left_column);
            break;
            case 'right':
                return parent::__get($this->_right_column);
            break;
            case 'level':
                return parent::__get($this->_level_column);
            break;
            case 'parent':
                return parent::__get($this->_parent_column);
            break;
            case 'children':
                return $this->getChildren();
            break;
            case 'descendants':
                return $this->getDescendants();
            break;
            case 'parents':
                return $this->getParents();
            break;
        }
        
        return parent::__get($column);
    }
    
    public function __set($column, $value)
    {
        switch ($column) 
        {
            case 'left':
                return parent::__set($this->_left_column, $value);
            break;
            case 'right':
                return parent::__set($this->_right_column, $value);
            break;
            case 'parent':
                return parent::__set($this->_parent_column, $value);
            break;
            case 'children':
            case 'parents':
            case 'descendants':
                return;
            break;
        }

        return parent::__set($column, $value);
    }
}