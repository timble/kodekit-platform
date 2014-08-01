<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
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
        $pathway = $this->getObject('application')->getPathway();

        $page = $this->getObject('application.pages')->getActive();
        if ($page->getLink()->query['view'] == 'categories')
        {
            $category = $article->getCategory();

            $pathway->addItem($category->title, $this->getTemplate()->getHelper('route')->category(array('entity' => $category)));
            $pathway->addItem($article->title, '');
        }

        if ($page->getLink()->query['view'] == 'articles') {
            $pathway->addItem($article->title, '');
        }

        return parent::_actionRender($context);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        $context->data->params = $this->getObject('application.pages')->getActive()->getParams('page');

        parent::_fetchData($context);
    }
}