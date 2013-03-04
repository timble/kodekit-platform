<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Articles router class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */

class ComArticlesRouter extends ComDefaultRouter
{
    public function buildRoute(&$query)
    {
        $segments = array();

        if(isset($query['Itemid'])) {
            $page = $this->getService('application.pages')->getPage($query['Itemid']);
        } else {
            $page = $this->getService('application.pages')->getActive();
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

    public function parseRoute($segments)
    {
        $vars = array();

        $page = $this->getService('application.pages')->getActive();

        $view  = $page->getLink()->query['view'];
        $count = count($segments);

        if($view == 'categories')
        {
            if($count)
            {
                $count--;
                $segment = array_shift( $segments );

                $vars['category'] = $segment;
                $vars['view'] = 'articles';
            }

            if($count)
            {
                $count--;
                $segment = array_shift( $segments) ;

                $vars['id']     = $segment;
                $vars['view']   = 'article';
                $vars['layout'] = 'default';
            }
        }

        if($view == 'articles')
        {
            $segment = array_shift( $segments) ;

            $vars['id']     = $segment;
            $vars['view']   = 'article';
            $vars['layout'] = 'default';
        }

        return $vars;
    }
}