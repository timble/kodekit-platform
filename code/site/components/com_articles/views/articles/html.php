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
 * Articles Html View Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewArticlesHtml extends ComArticlesViewHtml
{
    public function display()
    {
        //Get the parameters
        $params = $this->getService('application')->getParams();

        //Get the category
        $category = $this->getCategory();

        //Get the parameters of the active menu item
        if($page = $this->getService('application.pages')->getActive())
        {
            $menu_params = new JParameter($page->params);
            if(!$menu_params->get('page_title')) {
                $params->set('page_title', $category->title);
            }
        }
        else $params->set('page_title',	$category->title);

        //Set the pathway
        if($page->query['view'] == 'categories' ) {
            $this->getService('application')->getPathway()->addItem($category->title, '');
        }

        $this->assign('params'  , $params);
        $this->assign('category', $category);

        return parent::display();
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getService('com://site/articles.model.categories')
                         ->table('articles')
                         ->id($this->getModel()->getState()->category)
                         ->getRow();

        return $category;
    }
}