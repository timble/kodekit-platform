<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
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

        $this->_state
            ->insert('section'   , 'int')
            ->insert('category'  , 'int')
            ->insert('published' , 'int')
            ->insert('state'     , 'int')
            ->insert('created_by', 'int')
            ->insert('access'    , 'int')
            ->insert('featured'  , 'boolean')
            ->insert('deleted'   , 'int');

        $this->_state->remove('sort')->insert('sort', 'cmd', 'section_title');
    }

    public function getAuthors()
    {
        $query = $this->getTable()->getDatabase()->getQuery();
        $query->select(array('user.id', 'user.name'))
            ->distinct()
            ->order('user.name');

        $this->_buildQueryFrom($query);
        $this->_buildQueryJoins($query);
        $this->_buildQueryWhere($query);

        return $this->getTable()->select($query, KDatabase::FETCH_ROWSET);
    }

    public function getCategories()
    {
        $categories[0][]  = array('0', JText::_('Uncategorised'));

        $list = KFactory::tmp('admin::com.categories.model.categories')
            ->set('section', 'com_content')
            ->set('limit', 0)
            ->set('sort', 'title')
            ->getList();

        foreach($list as $item)
        {
            if(!isset($categories[$item->section])) {
                $categories[$item->section][] = array(-1, '- '.JText::_('Select').' -');
            }

            $categories[$item->section][] = array($item->id, $item->title);
        }

        return $categories;
    }

    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);

        $query->select('section.title AS section_title')
            ->select('category.title AS category_title')
            ->select('user.name AS created_by_name')
            ->select('IF(frontpage.content_id, 1, 0) AS featured')
            ->select('frontpage.ordering AS featured_ordering')
            ->select('group.name AS group_name');
    }

    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
         parent::_buildQueryJoins($query);

        $state = $this->_state;

        $query->join('LEFT', 'sections AS section', 'section.id = tbl.sectionid')
              ->join('LEFT', 'categories AS category', 'category.id = tbl.catid')
              ->join('LEFT', 'users AS user', 'user.id = tbl.created_by')
              ->join('LEFT', 'groups AS group', 'group.id = tbl.access');

        if(is_bool($state->featured) && $state->featured == true) {
            $query->join('RIGHT', 'content_frontpage AS frontpage', 'frontpage.content_id = tbl.id');
        } else {
            $query->join('LEFT', 'content_frontpage AS frontpage', 'frontpage.content_id = tbl.id');
        }
    }

    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->_state;

        if(is_numeric($state->state)) {
            $query->where('tbl.state', '=', $state->state);
        } else {
            $query->where('tbl.state', '<>', -2);
        }

        if($state->search) {
            $query->where('tbl.title', 'LIKE', '%'.$state->search.'%');
        }

        if(is_numeric($state->section)) {
            $query->where('tbl.sectionid', '=', $state->section );
        }

        if(is_numeric($state->category)) {
            $query->where('tbl.catid', '=',  $state->category);
        }

        if($state->created_by) {
            $query->where('tbl.created_by', '=', $state->created_by);
        }

        if(is_numeric($state->access)) {
            $query->where('tbl.access', '=', $state->access);
        }

        if($this->getTable()->isRevisable() && $state->deleted) {
            $query->where('tbl.deleted', '=', 1);
        }
    }

    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $state = $this->_state;

        $direction = strtoupper($state->direction);

        if(is_bool($state->featured) && $state->featured == true)
        {
            if($this->_state->sort == 'ordering')
            {
                $query->order('featured_ordering',  $direction);
            }
            else
            {
                $query->order($this->_state->sort, $direction)
                      ->order('featured_ordering', 'ASC');
            }
        }
        else
        {
            if($this->_state->sort == 'ordering')
            {
                $query->order('section_title', 'ASC')
                    ->order('category_title', 'ASC')
                    ->order('ordering', $direction);
            }
            else
            {
                $query->order($this->_state->sort, $direction)
                    ->order('section_title', 'ASC')
                    ->order('category_title', 'ASC')
                    ->order('ordering', 'ASC');
            }
        }
    }
}