<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Categorizable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
class DatabaseBehaviorCategorizable extends Library\DatabaseBehaviorAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config A ObjectConfig object with configuration options
     *
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_mixin' => true
        ));

        parent::_initialize($config);
    }

    /**
     * Get the category
     *
     * @return Library\DatabaseRowsetInterface
     */
    public function getCategory()
    {
        $model = $this->getObject('com:categories.model.categories');

        if (!$this->isNew())
        {
            //Get the category
            $category = $model->table($this->getTable()->getName())
                ->id($this->categories_category_id)
                ->fetch();
        }
        else $category = $model->fetch();

        return $category;
    }

    /**
     * Modify the select query
     *
     * If the query's params information includes a category property, auto-join the terms tables with the query and
     * select all the rows that are part of the category.
     *
     * @param Library\DatabaseContext $context
     */
    protected function _beforeSelect(Library\DatabaseContext $context)
    {
        $query  = $context->query;
        $params = $context->query->params;

        //Join the categories table
        $query->join(array('categories' => 'categories'), 'categories.categories_category_id = tbl.categories_category_id');
        $query->columns(array('category_title' => 'categories.title'));

        //Filter based on the category
        if ($params->has('category') && is_numeric($params->get('category')))
        {
            $query->where('tbl.categories_category_id IN :categories_category_id');

            if ($params->has('category_recurse') && $params->get('category_recurse') === true) {
                $query->where('tbl.categories.parent_id IN :categories_category_id', 'OR');
            }

            $query->bind(array('categories_category_id' => (array)$params->get('category')));
        }
    }
}