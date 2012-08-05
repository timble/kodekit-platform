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
        //Get the parameters
        $params = JFactory::getApplication()->getParams();

        //Get the contact
        $article = $this->getModel()->getData();

        //Get the parameters of the active menu item
        if($page = JFactory::getApplication()->getPages()->getActive())
        {
            $menu_params = new JParameter($page->params);
            if(!$menu_params->get('page_title')) {
                $params->set('page_title',	$article->title);
            }
        }
        else $params->set('page_title',	$article->title);

        //Set the page title
        JFactory::getDocument()->setTitle( $params->get( 'page_title' ) );

        //Set the breadcrumbs
        $pathway = JFactory::getApplication()->getPathway();

        if($page->link->query['view'] == 'categories')
        {
            $category = $article->getCategory();
            $pathway->addItem($category->title, $this->getTemplate()->getHelper('route')->category(array('row' => $category)));
            $pathway->addItem($article->title, '');
        }

        if($page->link->query['view'] == 'articles') {
            $pathway->addItem($article->title, '');
        }

        $this->assign('params'  , $params);
        $this->assign('user'  , JFactory::getUser());

        return parent::display();
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getService('com://site/articles.model.categories')
                         ->table('articles')
                         ->id($this->getModel()->getState()->category)
                         ->getItem();

        //Set the category image
        if (isset( $category->image ) && !empty($category->image))
        {
            $path = JPATH_IMAGES.'/stories/'.$category->image;
            $size = getimagesize($path);

            $category->image = (object) array(
                'path'   => '/'.str_replace(JPATH_ROOT.DS, '', $path),
                'width'  => $size[0],
                'height' => $size[1]
            );
        }

        return $category;
    }
}