<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Section html view class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewSectionHtml extends ComArticlesViewHtml
{
    public function display() {

        $params       = JComponentHelper::getParams('com_articles');
        $files_params = JComponentHelper::getParams('com_files');
        $user         = JFactory::getUser();

        $this->assign('aid', $user->get('aid', 0));
        $this->assign('params', $params);
        $this->assign('files_params', $files_params);

        return parent::display();
    }

    public function setCategories() {

        $section = $this->getModel()->getItem();

        $this->categories = new KConfig();

        if (!$section->isNew()) {

            $categories  = $this->getService('com://admin/articles.model.categories');
            $params      = JComponentHelper::getParams('com_articles');
            $sort_by_map = array(
                'newest' => array('id' => 'DESC'),
                'oldest' => array('id' => 'ASC'),
                'title'  => array('title' => 'ASC'),
                'order'  => array('ordering' => 'ASC'));
            $sort_by     = $sort_by_map[$params->get('sort_by')];

            $categories->set(array(
                'sort'      => key($sort_by),
                'direction' => current($sort_by),
                'section'   => $section->id,
                'aid'       => $this->aid));

            $this->categories->list  = $categories->getList();
            $this->categories->total = $categories->getTotal();
        }

        return $this;
    }

    public function setArticles() {

        $model   = $this->getModel();
        $state   = $model->getState();
        $section = $model->getItem();

        $this->articles = new KConfig();

        if (!$section->isNew()) {

            $params      = JComponentHelper::getParams('com_articles');
            $articles    = $this->getService('com://admin/articles.model.articles');
            $sort_by_map = array(
                'newest' => array('created' => 'DESC'),
                'oldest' => array('created' => 'ASC'),
                'author' => array('created_by_name', 'ASC'),
                'order'  => array('ordering' => 'ASC'));
            $sort_by     = $sort_by_map[$params->get('sort_by')];

            $articles->set(array(
                'sort'      => key($sort_by),
                'direction' => current($sort_by),
                'section'   => $section->id,
                'limit'     => $params->get('articles_per_page'),
                'offset'    => $state->offset,
                'aid'       => $this->aid,
                'state'     => 1));

            $this->articles->list  = $articles->getList();
            $this->articles->total = $articles->getTotal();
        }
        return $this;
    }

}