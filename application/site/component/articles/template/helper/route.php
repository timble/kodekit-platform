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
use Kodekit\Platform\Pages;

/**
 * Route Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Articles
 */
class TemplateHelperRoute extends Pages\TemplateHelperRoute
{
    public function article($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'view'   => 'article',
            'layout' => null,
            'format' => 'html'
        ));

        $article = $config->entity;

        $needles   = array();
        $needles[] = array('view' => 'article', 'id' => $article->id);

        $route = array(
            'view'   => $config->view,
            'layout' => $config->layout,
            'id'     => $article->getSlug(),
            'format' => $config->format
        );

        if ($article->isCategorizable())
        {
            $needles[]         = array('view' => 'category', 'id' => $article->getCategory()->id);
            $route['category'] = $article->getCategory()->getSlug();
        }

        if (($page = $this->_findPage($needles)) || ($article->isPageable() && ($page = $article->getPage()))) {
            $route['Itemid'] = $page->id;
        }

        return parent::route($route);
    }

    public function category($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'view'   => 'articles',
            'layout' => 'table'
        ));

        $category = $config->entity;

        $needles = array(
            array('view' => 'category', 'id' => $category->id),
        );

        $route = array(
            'view'     => $config->view,
            'layout'   => $config->layout,
            'category' => $category->getSlug(),
        );

        if ($page = $this->_findPage($needles))
        {
            if (isset($page->getLink()->query['layout'])) {
                $route['layout'] = $page->getLink()->query['layout'];
            }

            $route['Itemid'] = $page->id;
        };

        return parent::route($route);
    }

    /**
     * Comment route helper
     *
     * This function will forward to the appropriate router based on the name of the row.
     *
     * @param array $config An array of configuration options
     * @return string The route
     */
    public function comment($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'view'  => 'comments',
        ));

        //Forward the route call
        $function = Library\StringInflector::singularize($config->entity->getIdentifier()->name);
        $route    = $this->createHelper('route')->$function($config);

        return $route;
    }
}