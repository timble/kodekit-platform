<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Articles router class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */

class ArticlesRouter extends Library\DispatcherRouter
{
    public function build(Library\HttpUrl $url)
    {
        $segments = array();
        $query    = &$url->query;

        if(isset($query['Itemid'])) {
            $page = $this->getObject('application.pages')->getPage($query['Itemid']);
        } else {
            $page = $this->getObject('application.pages')->getActive();
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

        unset($query['category']);
        unset($query['id']);
        unset($query['view']);

        return $segments;
    }

    public function parse(Library\HttpUrl $url)
    {
        $vars = array();
        $path = &$url->path;

        $page = $this->getObject('application.pages')->getActive();

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

        return $vars;
    }
}