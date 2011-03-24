<?php
/**
 * @version      $Id$
 * @category	  Nooku
 * @package      Nooku_Server
 * @subpackage   Categories
 * @copyright    Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link          http://www.nooku.org
 */

/**
 * Orderable Database Behavior Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	 Nooku
 * @package     Nooku_Server
 * @subpackage  Categories    
 */
class ComCategoriesDatabaseBehaviorOrderable extends KDatabaseBehaviorOrderable
{
    protected $_section;

    public function _buildQueryWhere(KDatabaseQuery $query)
    {
        //Implement your where query here depending on your conditions
        $section = $this->_section ? $this->_section : $this->section;	
        $query->where('section', '=', $section);
    }

     /**
     * Saves the row to the database.
     *
     * This performs an intelligent insert/update and reloads the properties
     * with fresh data from the table on success.
     *
     * @return KDatabaseRowAbstract
     */
    protected function _beforeTableInsert(KCommandContext $context)
    {
        if (isset($this->ordering ))
        {
            $table  = $this->getTable();
            $db     = $table->getDatabase();
            $query  = $db->getQuery();

            //Build the where query
            $this->_buildQueryWhere($query);;

            $select = 'SELECT MAX(ordering) FROM `#__'.$table->getName().'`';
            $select .= (string) $query;
            $max =  (int) $db->select($select, KDatabase::FETCH_FIELD);

            if ($this->ordering <= 0){
                $this->ordering = $max + 1;
            } elseif ($this->ordering <= $max ){
                $this->reorder($this->ordering);
            }
        }
    }

    /**
     * Changes the rows ordering if the virtual order field is set. Order is
     * relative to the row's current position. Order is to be only set if section 
     * unchanged.
     * Inserts space in order sequence of new section if section changed.
     *
     * @param   KCommandContext Context
     */
    protected function _beforeTableUpdate(KCommandContext $context)
    {

	if (isset($this->ordering) )
        {
            if (isset($this->order) )
            {
                unset($this->old_parent);
                //default action
                parent::_beforeTableUpdate($context);
            } elseif (isset($this->old_parent)) {
                //make space for new entry
                $this->reorder($this->ordering);
            }
        }
    }

    /**
     * Reorders the old section if record has changed sections
     *
     * @param   KCommandContext Context
     */
    protected function _afterTableUpdate(KCommandContext $context)
    {
        if (isset($this->old_parent) && $this->old_parent != $this->section )
        {
            //section has changed,
            //tidy up the old section
            $this->_section = $this->old_parent;
            $this->reorder();
        }
    }

    /**
     * Resets the order of all rows
     * Resetting starts at $base to allow creating space in sequence 
     * for later record insertion.
     *
     * @return      KDatabaseTableAbstract
     */
    public function reorder($base=0)
    {
        $table  = $this->getTable();
        $db     = $table->getDatabase();
        $query  = $db->getQuery();

        //Build the where query
        $this->_buildQueryWhere($query);
        if ($base && is_numeric($base) ) {
            $query->where('ordering', '>=', $base);
        } 

        $db->execute("SET @order = $base");
        $db->execute(
             'UPDATE #__'.$table->getBase().' '
            .'SET ordering = (@order := @order + 1) '
            .(string) $query.' '
            .'ORDER BY ordering ASC'
        );

        return $this;
    }




}


