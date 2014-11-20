<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Categories Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
class ModelCategories extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        // Set the state
        $this->getState()
            ->insert('table', 'string', $this->getIdentifier()->package)
            ->insert('parent', 'int')
            ->insert('published', 'boolean')
            ->insert('distinct', 'string')
            ->insert('access', 'int')
            ->insert('category', 'int')
            ->insert('sort', 'cmd', 'ordering');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('searchable'),
        ));

        parent::_initialize($config);
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        //Exclude joins if counting records
        if (!$query->isCountQuery()) {
            if ($state->table) {
                $query->columns(array('count'));

                $subquery = $this->getObject('lib:database.query.select')
                    ->columns(array('categories_category_id', 'count' => 'COUNT(categories_category_id)'))
                    ->table($state->table)
                    ->group('categories_category_id');

                $query->join(array('content' => $subquery), 'content.categories_category_id = tbl.categories_category_id');
            }
        }

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();

        if ($state->table) {
            $query->where('tbl.table ' . (is_array($state->table) ? 'IN' : '=') . ' :table')->bind(array('table' => $state->table));
        }

        if (is_numeric($state->parent)) {
            $query->where('tbl.parent_id ' . (is_array($state->parent) ? 'IN' : '=') . ' :parent')->bind(array('parent' => $state->parent));
        }

        if (is_numeric($state->category)) {
            $query->where('tbl.parent_id ' . (is_array($state->category) ? 'IN' : '=') . ' :parent')->bind(array('parent' => $state->category));
        }

        if (is_bool($state->published)) {
            $query->where('tbl.published = :published');

            if ($state->table) {
                $query->where('content.published = :published');
            }

            $query->bind(array('published' => (int)$state->published));
        }

        if (is_bool($state->access)) {
            $query->where('tbl.access <= :access')->bind(array('access' => (int)$state->access));
        }
    }

    protected function _buildQueryGroup(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();
        if ($state->distinct) {
            $query->distinct();
            $query->group($state->distinct);
        } else $query->group('tbl.categories_category_id');
    }
}