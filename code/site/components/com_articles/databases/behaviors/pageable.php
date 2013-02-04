<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Search
 * @copyright      Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Pageable Database Behavior Class.
 *
 * @author        Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package       Nooku_Server
 * @subpackage    Articles
 */
class ComArticlesDatabaseBehaviorPageable extends KDatabaseBehaviorAbstract
{
    protected $_constraints;

    protected $_pages;

    /**
     * @var int The ID of the user to get accessible pages from.
     */
    protected $_user;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_user = $this->getService('com://admin/users.model.users')->id($config->user)->getRow();
    }

    protected function _beforeTableSelect(KCommandContext $context)
    {
        $this->_filterByPages($context);
    }

    protected function _filterByPages(KCommandContext $context)
    {
        $constraints = $this->_getConstraints();

        if ($categories = $constraints['categories']) {
            $context->query->where('categories.categories_category_id IN :categories')
                ->bind(array('categories' => $categories));
        }

        if ($articles = $constraints['articles']) {
            // TODO The query builder doesn't support queries like WHERE ... OR (... AND ...). Because of this
            // I need to make the inner condition a single one. This needs improvement.
            $db = $this->getMixer()->getDatabase();

            $query = $this->getService('koowa:database.query.select')->table('articles')
                ->columns('articles_article_id')->where('articles_article_id IN :articles')
                ->where('access IN :access')->bind(array(
                'articles' => $articles,
                'access'   => (array) ($this->_user->guest ? 0 : array(0, 1))));

            if ($articles = $db->select($query, KDatabase::FETCH_FIELD_LIST)) {
                $context->query->where('tbl.articles_article_id IN :articles', 'OR')
                    ->bind(array('articles' => $articles));
            }
        }
    }

    protected function _getConstraints()
    {
        if (!$this->_constraints) {

            $constraints = array('categories' => array(), 'articles' => array());

            if ($pages = $this->_getPages()) {

                foreach ($pages as $page) {

                    $link = $page->getLink();

                    if ($page->link_url == 'index.php?option=com_articles&view=articles') {
                        // Particular case ... all articles from all categories.
                        $constraints['categories'][] = 0;
                    }

                    if (isset($link->query['category'])) {
                        $constraints['categories'][] = (int) $link->query['category'];
                    }

                    if (($link->query['view'] == 'article') && isset($link->query['id'])) {
                        $constraints['articles'][] = $link->query['id'];
                    }
                }

                if (in_array(0, $constraints['categories'])) {
                    // No filtering by category.
                    $constraints['categories'] = array();
                }
            }

            $this->_constraints = $constraints;
        }

        return $this->_constraints;
    }

    protected function _getPages()
    {
        if (!$this->_pages) {

            $user = $this->_user;

            $access = array(0);
            $groups = array(0);

            if (!$user->guest) {
                $access[] = 1;
                $groups   = array_merge($groups, $user->getGroups());
            }

            $pages = $this->getService('com://admin/pages.model.pages')->application('site')->published(true)
                ->getRowset()->find(array(
                'component_name' => 'com_' . $this->getMixer()
                    ->getIdentifier()->package,
                'users_group_id' => $groups,
                'access'         => $access));

            $this->_pages = $pages;
        }

        return $this->_pages;
    }

    public function getPage()
    {
        $page = null;

        if (!$this->isNew()) {

            $pages = $this->_getPages();

            $needles = array(
                array('view' => 'article', 'id' => $this->id),
                array('view' => 'articles', 'category' => $this->categories_category_id));

            $page = $pages->find(array('link' => $needles));

            if (is_null($page)) {
                // Look for a category page.
                $category = $this->getService('com://admin/categories.model.categories')->category($this->category)
                    ->getRow();
                $page     = $pages->find(array(
                    'link' => array(
                        array(
                            'view'     => 'categories',
                            'category' => $category->id))));
            }

            if (is_null($page)) {
                // Look for an un-filtered articles view page.
                $page = $pages->find(array('link_url' => 'index.php?option=com_articles&view=articles'));
            }
        }

        return $page;
    }
}