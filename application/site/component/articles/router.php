<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Router
 *
 * @author   Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
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