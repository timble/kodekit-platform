<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articles Table Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesModelArticles extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('section'   , 'int')
            ->insert('category'  , 'int')
            ->insert('state'     , 'int')
            ->insert('created_by', 'int')
            ->insert('access'    , 'int')
            ->insert('featured'  , 'boolean')
            ->insert('trashed'   , 'int');

        $this->getState()->remove('sort')->insert('sort', 'cmd', 'section_title');
    }

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array(
        	'section_title'     => 'sections.title',
            'category_title'    => 'categories.title',
            'created_by_name'   => 'users.name',
            'created_by_id'     => 'users.id',
            'featured_ordering' => 'frontpage.ordering',
            'group_name'        => 'groups.name',
        	'featured'          => 'IF(frontpage.content_id, 1, 0)'
        ));
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);
        
        $state = $this->getState();

        $query->join(array('sections' => 'sections'), 'sections.id = tbl.sectionid')
              ->join(array('categories' => 'categories'), 'categories.id = tbl.catid')
              ->join(array('users' => 'users'), 'users.id = tbl.created_by')
              ->join(array('groups' => 'groups'), 'groups.id = tbl.access');

        $query->join(array('frontpage' => 'content_frontpage'), 'frontpage.content_id = tbl.id', $state->featured ? 'RIGHT' : 'LEFT');
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        
        $state = $this->getState();

        if(is_numeric($state->state)) 
        {
            $query->where('tbl.state = :state')
                ->bind(array('state' => $state->state));
        } 
        else 
        {
            $query->where('tbl.state <> :state')
                ->bind(array('state' => -2));
        }

        if($state->search) 
        {
            $query->where('tbl.title LIKE :search')
                ->bind(array('search' => '%'.$state->search.'%'));
        }

        if(is_numeric($state->section)) 
        {
            $query->where('tbl.sectionid = :section')
                ->bind(array('section' => $state->section));
        }

        if(is_numeric($state->category)) 
        {
            $query->where('tbl.catid = :category')
                ->bind(array('category' => $state->category));
        }

        if($state->created_by) 
        {
            $query->where('tbl.created_by = :created_by')
                ->bind(array('created_by' => $state->created_by));
        }

        if(is_numeric($state->access)) 
        {
            $query->where('tbl.access = :access')
                ->bind(array('access' => $state->access));
        }
        
        if($this->getTable()->isRevisable() && $state->trashed) 
        {
            $query->where('tbl.deleted = :trashed')
                ->bind(array('trashed' => 1));
        }
    }

    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();

        $direction = strtoupper($state->direction);

        if (is_bool($state->featured) && $state->featured == true) 
        {
            if ($state->sort != 'ordering') 
            {
                $query->order($this->getState()->sort, $direction)
                    ->order('featured_ordering', 'ASC');
            } 
            else $query->order('featured_ordering',  $direction);
        } 
        else 
        {
            if ($state->sort == 'ordering') 
            {
                $query->order('section_title', 'ASC')
                    ->order('category_title', 'ASC')
                    ->order('ordering', $direction);
            } 
            else 
            {
                $query->order($state->sort, $direction)
                    ->order('section_title', 'ASC')
                    ->order('category_title', 'ASC')
                    ->order('ordering', 'ASC');
            }
        }
    }
}