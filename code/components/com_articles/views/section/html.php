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

        if (!$section->isNew()) {

            $categories  = $this->getService('com://admin/articles.model.categories');
            $params      = JComponentHelper::getParams('com_articles');
            $sort_by_map = array(
                'newest' => array('id' => 'DESC'),
                'oldest' => array('id' => 'ASC'),
                'title'  => array('title' => 'ASC'),
                'order'  => array('ordering' => 'ASC'));
            $sort_by     = $sort_by_map[$params->get('sort_by')];

            $this->categories = $section->getCategories(array(
                'model_state' => array(
                    'published' => 1,
                    'sort'      => key($sort_by),
                    'direction' => current($sort_by),
                    'aid'       => $this->aid)));
        }

        return $this;
    }

    public function setArticles() {

        $model   = $this->getModel();
        $state   = $model->getState();
        $section = $model->getItem();

        if (!$section->isNew()) {

            $params = JComponentHelper::getParams('com_articles');

            $sort_by_map = array(
                'newest' => array('created' => 'DESC'),
                'oldest' => array('created' => 'ASC'),
                'order'  => array('ordering' => 'ASC'));
            $sort_by     = $sort_by_map[$params->get('sort_by')];

            $this->articles = $section->getArticles(array(
                'model_state' => array(
                    'sort'      => key($sort_by),
                    'direction' => current($sort_by),
                    'limit'     => $params->get('articles_per_page'),
                    'offset'    => $state->offset,
                    'aid'       => $this->aid,
                    'state'     => $this->getService('com://site/articles.controller.article')->canEdit() ? null : 1)));
        }
        return $this;
    }

}