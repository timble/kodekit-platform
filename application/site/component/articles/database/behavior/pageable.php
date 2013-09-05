<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Pageable Database Behavior
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
 */
class ArticlesDatabaseBehaviorPageable extends Library\DatabaseBehaviorAbstract
{
    protected $_constraints;

    protected $_pages;

    /**
     * @var int The ID of the user to get accessible pages from.
     */
    protected $_user;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_user = $this->getObject('com:users.model.users')->id($config->user)->getRow();
    }

    protected function _beforeTableSelect(Library\CommandContext $context)
    {
        $this->_filterByPages($context);
    }

    protected function _filterByPages(Library\CommandContext $context)
    {
        $base_where = '';

        foreach ($context->query->where as $where) {
            $base_where .= ' ' . $where['combination'] . ' ' . $where['condition'];
        }

        if (!empty($base_where)) {
            $base_where = ' AND ' . $base_where;
        }

        $constraints = $this->_getConstraints();

        if ($categories = $constraints['categories'])
        {
            $context->query->where('categories.categories_category_id IN :categories')
                ->bind(array('categories' => $categories));
        }

        if ($parents = $constraints['category_parents'])
        {
            $context->query->where('(categories.parent_id IN :parents' . $base_where . ')', 'OR')
                ->bind(array('parents' => $parents));
        }

        if ($articles = $constraints['articles'])
        {
            $context->query->where('(tbl.articles_article_id IN :articles' . $base_where . ')', 'OR')
                ->bind(array('articles' => $articles));
        }
    }

    protected function _getConstraints()
    {
        if (!$this->_constraints) {

            $constraints = array('categories' => array(), 'articles' => array(), 'category_parents' => array());

            if ($pages = $this->_getPages())
            {
                foreach ($pages as $page)
                {
                    $link = $page->getLink();

                    // Particular case ... all articles from all categories.
                    if ($page->link_url == 'option=com_articles&view=articles') {
                        $constraints['categories'][] = 0;
                    }

                    if (isset($link->query['category']))
                    {
                        // Assume view=articles
                        if ($link->query['view'] != 'categories') {
                            $constraints['categories'][] = (int) $link->query['category'];
                        } else {
                            $constraints['category_parents'][] = (int) $link->query['category'];
                        }
                    }

                    if (($link->query['view'] == 'article') && isset($link->query['id'])) {
                        $constraints['articles'][] = $link->query['id'];
                    }
                }

                // No filtering by category.
                if (in_array(0, $constraints['categories'])) {
                    $constraints['categories'] = array();
                }
            }

            $this->_constraints = $constraints;
        }

        return $this->_constraints;
    }

    protected function _getPages()
    {
        if (!$this->_pages)
        {
            $user = $this->_user;

            $needles = array(
                'users_group_id' => array_merge(array(0), $user->getGroups()),
                'component_name' => 'com_'.$this->getMixer()->getIdentifier()->package);

            if ($user->guest) {
                $needles['access'] = 0;
            }

            $pages = $this->getObject('com:pages.model.pages')
                           ->application('site')
                           ->published(true)
                           ->getRowset()->find($needles);

            $this->_pages = $pages;
        }

        return $this->_pages;
    }

    public function getPage()
    {
        $page = null;

        if (!$this->isNew())
        {
            $pages = $this->_getPages();

            $needles = array(
                array('view' => 'article', 'id' => $this->id),
                array('view' => 'articles', 'category' => $this->categories_category_id));

            $page = $pages->find(array('link' => $needles));

            if (is_null($page))
            {
                // Look for a category page.
                $category = $this->getObject('com:categories.model.categories')->category($this->category)->getRow();
                $page     = $pages->find(array('link' => array(
                        array(
                            'view'     => 'categories',
                            'category' => $category->id
                        )
                )));
             }

            // Look for an un-filtered articles view page.
            if (is_null($page)) {
                $page = $pages->find(array('link_url' => 'option=com_articles&view=articles'));
            }
        }

        return $page;
    }
}