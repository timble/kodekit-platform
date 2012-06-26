<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Category html view class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewCategoryHtml extends ComArticlesViewHtml
{
    public function display()
    {
        $params       = JComponentHelper::getParams('com_articles');
        $files_params = JComponentHelper::getParams('com_files');
        $user         = JFactory::getUser();

        $aid = $user->get('aid', 0);

        $model    = $this->getModel();
        $state    = $model->getState();
        $category = $model->getItem();

        if (!$category->isNew())
        {
            $sort_by_map = array(
                'newest' => array('created' => 'DESC'),
                'oldest' => array('created' => 'ASC'),
                'title'  => array('title' => 'ASC'),
                'order'  => array('ordering' => 'ASC'));
            $sort_by     = $sort_by_map[$params->get('sort_by')];

            $articles = $category->getArticles(array(
                'model_state' => array(
                    'limit'     => $params->get('articles_per_page'),
                    'offset'    => $state->offset,
                    'sort'      => key($sort_by),
                    'direction' => current($sort_by),
                    'category'  => $category->id,
                    'aid'       => $aid,
                    'state'     => $this->getService('com://site/articles.controller.article')->canEdit() ? null : 1)));

            $this->assign('articles', $articles->list);
            $this->assign('total_articles', $articles->count);
        }

        $menus   = JSite::getMenu();
        $menu    = $menus->getActive();
        $pathway = JFactory::getApplication()->getPathway();

        // Handle the breadcrumbs
        if ($menu && $menu->query['view'] == 'section') {
            $pathway->addItem(htmlspecialchars($category->title, ENT_QUOTES));
        }

        $this->assign('aid', $aid);
        $this->assign('params', $params);
        $this->assign('files_params', $files_params);

        return parent::display();
    }
}