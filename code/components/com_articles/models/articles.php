<?php
/**
 * @version     $Id: articles.php 1633 2011-06-07 19:24:17Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articles Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesModelArticles extends KModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_state
            ->insert('section', 'int', -1)
            ->insert('category', 'int', -1)
            ->insert('featured', 'boolean')
            ->insert('state', 'int')
            ->insert('created_by', 'int')
            ->insert('access', 'int')
            ->insert('year', 'int')
            ->insert('month', 'int');

        $this->_state->remove('sort')->insert('sort', 'cmd', 'section_title');
    }

    public function getParameters($layout = 'default')
    {
        $parameters = KFactory::get('lib.joomla.application')->getParams('com_articles');
        $parameters->def('show_page_title', 1);

        if(in_array($layout, array('featured', 'category_blog', 'category_default', 'section_blog', 'section_default')))
        {
            $parameters->def('show_description', 1);
            $parameters->def('show_description_image', 1);
            $parameters->set('show_pagination', 1);
        }

        if(in_array($layout, array('featured', 'category_blog', 'section_blog')))
        {
            $parameters->def('num_intro_articles', 4);
    		$parameters->def('num_leading_articles', 1);
    		$parameters->def('num_links', 4);
            $parameters->def('multi_column_order', 0);
        }

        if($layout == 'section_default') {
            $parameters->def('show_categories', 1);
        }

        return $parameters;
    }

    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);

        $query->select('section.title AS section_title')
            ->select('category.title AS category_title')
            ->select('category.alias AS category_slug')
            ->select('user.name AS created_by_name')
            ->select('IF(frontpage.content_id, 1, 0) AS featured')
            ->select('IF(tbl.created_by_alias, tbl.created_by_alias, user.name) AS author');
    }

    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
        $query->join('LEFT', 'sections AS section', 'section.id = tbl.sectionid')
            ->join('LEFT', 'categories AS category', 'category.id = tbl.catid')
            ->join('LEFT', 'users AS user', 'user.id = tbl.created_by')
            ->join('LEFT', 'content_frontpage AS frontpage', 'frontpage.content_id = tbl.id');
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

        if($state->featured) {
            $query->where('frontpage.content_id', '>', 1);
        }

        if($state->search) {
            $query->where('tbl.title', 'LIKE', '%'.$state->search.'%');
        }

        if($state->section > -1) {
            $query->where('tbl.sectionid', '=', $state->section );
        }

        if($state->category > -1) {
            $query->where('tbl.catid', '=',  $state->category);
        }

        if($state->created_by) {
            $query->where('tbl.created_by', '=', $state->created_by);
        }

        if(is_numeric($state->access)) {
            $query->where('tbl.access', '=', $state->access);
        }

        if($state->year) {
            $query->where('YEAR(tbl.created)', '=', $state->year);
        }

        if($state->month) {
            $query->where('MONTH(tbl.created)', '=', $state->month);
        }
    }

    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $sort       = $this->_state->sort;
        $direction  = strtoupper($this->_state->direction);

        if($sort) {
            $query->order($this->getTable()->mapColumns($sort), $direction);
        }
    }

    // Override to fix framework level bugs.
    protected function _buildQueryLimit(KDatabaseQuery $query)
    {
        $limit = $this->_state->limit;

        if($limit = $this->_state->limit) {
             $query->limit($limit, $this->_state->offset);
        }
    }

    // Override to fix framework level bugs.
    public function set( $property, $value = null )
    {
        if(is_object($property)) {
    		$property = (array) KConfig::toData($property);
    	}

    	if(is_array($property))
        {
            foreach($property as $key => $value)
            {
                if(isset($this->_state->$key) && $this->_state->$key != $value)
                {
                    $changed = true;
                    break;
                }
            }

        	$this->_state->setData($property);
        }
        else
        {
            if(isset($this->_state->$property) && $this->_state->$property != $value) {
                $changed = true;
            }

        	$this->_state->$property = $value;
        }

        if($changed)
        {
            unset($this->_list);
            unset($this->_item);
            unset($this->_total);
        }

        // If limit has been changed, adjust offset accordingly.
        if((is_array($property) && isset($property['limit']) && !isset($property['offset']) || $property == 'limit')
            && $limit = $this->_state->limit)
        {
            $this->_state->offset = $limit != 0 ? (floor($this->_state->offset / $limit) * $limit) : 0;
        }

        return $this;
    }
}