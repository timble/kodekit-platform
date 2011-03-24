<?php
/**
 * @version    	$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Categories
 * @copyright  	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license    	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link       	http://www.nooku.org
 */

/**
 * Categories Model Class
 *
 * @author		John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Categories    
 */
class ComCategoriesModelCategories extends ComDefaultModelDefault
{	
    protected $child_id;

    public function __construct(KConfig $config)
	{
        parent::__construct($config);
		
        // Set the state
        $this->_state
            ->insert('section'   , 'string')
            //as 'section' is ambiguous, we need an alias
            ->insert('parent'    , 'string')
            ->insert('published' , 'int')
            ->insert('distinct'  , 'string');

    }
	
    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);
        
        if ( $this->_state->section)
        {
            if ( $this->_state->section == 'com_content' || is_numeric($this->_state->section)){
                $query->select('sections.title AS section_title')
                      ->select('sections.id AS section_id')
                      ->select('SUM( IF(content.state <> -2,1,0)) activecount')
                      ->select('SUM( IF(content.state = -2,1,0)) trashcount');
            } else {
                $query->select('SUM(IF(child.catid,1,0)) activecount');
            }
        }
        $query->select('order.maxorder');
    }


    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
        //Exclude joins if counting records
        if(!$query->count)
        {
            if ( $this->_state->section)
            {
                if ($this->_state->section == 'com_content' || is_numeric($this->_state->section)){
                    $query->join('LEFT','content AS content','content.catid = tbl.id');
                    $query->join('LEFT','sections AS sections','sections.id = tbl.section');
                } else {
                    $query->join('LEFT',substr($this->_state->section,4).' AS child','child.catid = tbl.id');
                }
            }
        
            $query->join[]=array('type' => 'LEFT',
	        'table' => '(SELECT section ordersection, MAX(ordering) maxorder FROM #__categories 
                            GROUP BY section) AS order', 
                'condition' => array('order.ordersection = tbl.section'));
        }    
        
        parent::_buildQueryJoins($query);
    }


    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        $state = $this->_state;

        if($state->search) {
            $query->where('name', 'LIKE',  '%'.$state->search.'%');
        }
		
        //select overall section
        if ($state->section) 
        {
            if( $state->section == 'com_content' ) {
                $query->where('section', 'NOT LIKE', 'com%');
            } else {
                $query->where('section', 'IN', $state->section);
            }
        }

        //select parent section within com_content
        if ($state->parent) {
            $query->where('section', 'IN', $state->parent);
        }

        if (is_numeric($state->published)) {
            $query->where('tbl.published', '=', $state->published);
        }
	      
        parent::_buildQueryWhere($query);
    }

    protected function _buildQueryGroup(KDatabaseQuery $query)
    {
        $state = $this->_state;
        if( $state->distinct ) {
            $query->distinct();
            $query->group($state->distinct);    
        } else {
            $query->group('tbl.id');
        }
    }

    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $sort = $this->_state->sort;
        $direction  = strtoupper($this->_state->direction);

	    if ( $sort) {
            $query->order($this->getTable()->mapColumns($sort), $direction);
        }

        if (empty($sort))
        {
            if ($this->_state->section == 'com_content'){
                $query->order('sections.ordering','ASC');
            }
        }
         
	    $query->order('ordering', 'ASC');
    }
}