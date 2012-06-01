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
 * Article html view class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewArticleHtml extends ComArticlesViewHtml
{

    public function display() {

        $menus   = JSite::getMenu();
        $menu    = $menus->getActive();
        $pathway = JFactory::getApplication()->getPathway();

        $article = $this->getModel()->getItem();

        // Handle the breadcrumbs
        if ($menu && $menu->query['view'] != 'article') {
            $pathway->addItem($article->title, '');
        }

        $user = JFactory::getUser();

        $this->assign('user', $user);
        $this->assign('params', JComponentHelper::getParams('com_articles'));

        return parent::display();
    }

}