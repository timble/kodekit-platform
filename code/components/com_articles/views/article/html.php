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
 * Article Html View Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewArticleHtml extends ComArticlesViewHtml
{
    public function display()
    {
        $menus   = JFactory::getApplication()->getMenu();
        $menu    = $menus->getActive();
        $pathway = JFactory::getApplication()->getPathway();

        $article = $this->getModel()->getItem();

        // Handle the breadcrumbs
        if ($menu)
        {
            switch ($menu->query['view'])
            {
                case 'section':
                    $category = $article->getCategory();
                    $pathway->addItem(htmlspecialchars($category->title, ENT_QUOTES),
                        'index.php?option=com_articles&view=category&id=' . $category->id);
                    $pathway->addItem(htmlspecialchars($article->title, ENT_QUOTES), '');
                    break;

                case 'category':
                    $pathway->addItem(htmlspecialchars($article->title, ENT_QUOTES), '');
                    break;
            }
        }

        $user = JFactory::getUser();

        $this->assign('user', $user);
        $this->assign('params', JComponentHelper::getParams('com_articles'));

        return parent::display();
    }

}