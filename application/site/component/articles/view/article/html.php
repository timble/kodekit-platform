<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Article Html View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class ViewArticleHtml extends ViewHtml
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