<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Newsfeeds Model Class
 *
 * @author      Babs Gösgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */
class ComNewsfeedsModelNewsfeeds extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('published', 'boolean')
            ->insert('category' , 'slug');
    }

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array('category_title' => 'categories.title'));
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        // Exclude joins if counting records.
        if(!$query->isCountQuery()) {
            $query->join(array('categories' => 'categories'), 'categories.id = tbl.catid');
        }
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if (is_bool($state->published)) {
            $query->where('tbl.published = :published')->bind(array('published' => (int) $state->published));
        }

        if ($state->category) {
            $query->where('tbl.catid = :category')->bind(array('category' => (int) $state->category));
        }

        if (!empty($state->search)) {
            $query->where('tbl.name LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        }
    }

    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();
        
        $sort       = $state->sort;
        $direction  = strtoupper($state->direction);

        if($sort) {
            $query->order($this->getTable()->mapColumns($sort), $direction);
        }

        if(array_key_exists('ordering', $this->getTable()->getColumns())) {
            $query->order('categories.title, tbl.ordering', 'ASC');
        }
    }
}