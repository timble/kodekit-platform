<?php
/**
 * @version    	$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Categories
 * @copyright  	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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
        $this->getState()
            ->insert('section'   , 'string')
            ->insert('parent'    , 'string')
            ->insert('published' , 'boolean')
            ->insert('distinct'  , 'string');

    }

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        $state = $this->getState();
        
        if ($state->section) {
            if ( $state->section == 'com_content' || is_numeric($state->section)){
                $query->columns(array(
                	'section_title' => 'section.title',
                    'activecount' => 'SUM(IF(content.state <> -2, 1, 0))',
                ));
            } else {
                $query->columns(array('activecount' => 'SUM(IF(child.catid, 1, 0))'));
            }
        }
    }


    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();
        
        //Exclude joins if counting records
        if(!$query->isCountQuery())
        {
            if ($state->section) {
                if ($state->section == 'com_content' || is_numeric($state->section)){
                    $query->join(array('content' => 'content'), 'content.catid = tbl.id');
                    $query->join(array('section' => 'sections'), 'section.id = tbl.section');
                } else {
                    $query->join(array('child' => substr($state->section, 4)), 'child.catid = tbl.id');
                }
            }
        }

        parent::_buildQueryJoins($query);
    }


    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();

        if($state->search) {
            $query->where('tbl.title LIKE %:search%')->bind(array('search' => $state->search));
        }

        //select overall section
        if ($state->section)
        {
            if( $state->section == 'com_content' ) {
                $query->where('tbl.section NOT LIKE :section')->bind(array('section' => 'com%'));
            } else {
                $query->where('tbl.section '.(is_array($state->section) ? 'IN' : '=').' :section')->bind(array('section' => $state->section));
            }
        }

        //select parent section within com_content
        if ($state->parent) {
            $query->where('tbl.section '.(is_array($state->section) ? 'IN' : '=').' :parent')->bind(array('parent' => $state->parent));
        }

        if (is_bool($state->published)) {
            $query->where('tbl.published = :published')->bind(array('published' => (int) $state->published));
        }

        parent::_buildQueryWhere($query);
    }

    protected function _buildQueryGroup(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();
        if( $state->distinct ) 
        {
            $query->distinct();
            $query->group($state->distinct);
        } 
        else $query->group('tbl.id');
    }

    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();
        
        $sort = $state->sort;
        $direction  = strtoupper($state->direction);

	    if ( $sort) {
            $query->order($this->getTable()->mapColumns($sort), $direction);
        }

        if (empty($sort))
        {
            if ($state->section == 'com_content'){
                $query->order('section.ordering','ASC');
            }
        }

	    $query->order('ordering', 'ASC');
    }
}