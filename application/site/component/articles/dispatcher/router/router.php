<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Router
 *
 * @author   Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class DispatcherRouter extends Library\DispatcherRouter
{
    public function build(Library\HttpUrlInterface $url)
    {
        $segments = array();
        $query    = &$url->query;

        if(isset($query['Itemid'])) {
            $page = $this->getObject('pages')->getPage($query['Itemid']);
        } else {
            $page = $this->getObject('pages')->getActive();
        }

        $view = $page->getLink()->query['view'];

        if($view == 'categories')
        {
            if(isset($query['category']))
            {
                if($query['category'] != $page->getLink()->query['category']) {
                    $segments[] = $query['category'];
                }
            }

            if(isset($query['id'])) {
                $segments[] = $query['id'];
            }
        }

        if($view == 'articles')
        {
            if(isset($query['id'])) {
                $segments[] = $query['id'];
            }
        }

        //Todo : move to the the generic component router
        if(isset($page->getLink()->query['layout']) && isset($query['layout']))
        {
            if($page->getLink()->query['layout'] == $query['layout']) {
                unset($query['layout']);
            }
        }

        if(isset($query['view']) && $query['view'] == 'comments') {
            $segments[] = 'comments';
        }

        unset($query['category']);
        unset($query['id']);
        unset($query['view']);

        return $segments;
    }

    public function parse(Library\HttpUrlInterface $url)
    {
        $vars = array();
        $path = &$url->path;

        $page = $this->getObject('pages')->getActive();

        $view  = $page->getLink()->query['view'];
        $count = count($path);

        if($view == 'categories')
        {
            if($count)
            {
                $count--;
                $segment = array_shift( $path );

                $vars['category'] = $segment;
                $vars['view'] = 'articles';
            }

            if($count)
            {
                $count--;
                $segment = array_shift( $path) ;

                $vars['id']     = $segment;
                $vars['view']   = 'article';
                $vars['layout'] = 'default';
            }
        }

        if($view == 'articles')
        {
            $segment = array_shift( $path) ;

            $vars['id']     = $segment;
            $vars['view']   = 'article';
            $vars['layout'] = 'default';
        }

        if(count($path) && $path[0] == 'comments')
        {
            $segment = array_shift( $path) ;

            $vars['view']    = 'comments';
            $vars['article'] = $segment;

            //Remove the (parent resource) id to prevent an edit action from being executed if the page url is unique.
            $vars['id']      = null;
        }

        return $vars;
    }
}