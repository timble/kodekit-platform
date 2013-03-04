<?php
/**
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
    public function render()
    {
        //Get the parameters
        $params = $this->getService('application')->getParams();

        //Get the contact
        $article = $this->getModel()->getData();

        //Get the parameters of the active menu item
        if($page = $this->getService('application.pages')->getActive())
        {
            $menu_params = new JParameter($page->params);
            if(!$menu_params->get('page_title')) {
                $params->set('page_title',	$article->title);
            }
        }
        else $params->set('page_title',	$article->title);

        //Set the breadcrumbs
        $pathway = $this->getService('application')->getPathway();

        if($page->getLink()->query['view'] == 'categories')
        {
            $category = $this->getCategory();
            $pathway->addItem($category->title, $this->getTemplate()->getHelper('route')->category(array('row' => $category)));
            $pathway->addItem($article->title, '');
        }

        if($page->getLink()->query['view'] == 'articles') {
            $pathway->addItem($article->title, '');
        }

        $this->params = $params;
        return parent::render();
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