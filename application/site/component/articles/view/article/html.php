<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Article Html View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Articles
 */
class ArticlesViewArticleHtml extends ArticlesViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $article = $this->getModel()->fetch();

        //Set the breadcrumbs
        $page    = $this->getObject('pages')->getActive();
        $pathway = $this->getObject('pages')->getPathway();

        if ($page->getLink()->query['view'] == 'categories')
        {
            $category = $article->getCategory();
            $url      = $this->getTemplate()->createHelper('route')->category(array('entity' => $category));

            $pathway[] = array(
                'title' => $category->title,
                'link'  => $url,
            );

            $pathway[] = array('title' => $article->title);
        }

        if ($page->getLink()->query['view'] == 'articles') {
            $pathway[] = array('title' => $article->title);
        }

        return parent::_actionRender($context);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        $context->data->params = $this->getObject('pages')->getActive()->getParams('page');

        parent::_fetchData($context);
    }
}