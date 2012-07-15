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

        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getItem($query['Itemid']);

        $menu_view = empty($item->query['view']) ? null : $item->query['view'];
        //$menu_catid = empty($item->query['catid']) ? null : $item->query['catid'];
        $menu_id = empty($item->query['id']) ? null : $item->query['id'];

        if (isset($query['view']))
        {
            $query_view = $query['view'];
            if (empty($query['Itemid'])) {
                $segments[] = $query['view'];
            }
            unset($query['view']);
        }

        if ($menu_view == 'article' && (isset($query['id']) && ($menu_id == intval($query['id']))))
        {
            // Article attached to a menu item.
            unset($query['view']);
            unset($query['catid']);
            unset($query['id']);
        }

        if (isset($query['catid']))
        {
            if (isset($query_view) && $query_view == 'article' && $menu_view == 'section') {
                // Include the catid segment on articles being displayed from section menu items.
                $segments[] = $query['catid'];
            }
            unset($query['catid']);
        }

        if (isset($query['id']))
        {
            if (empty($query['Itemid']) || (isset($query_view) && $query_view != $menu_view)) {
                // Include the query id on any view being accessed from a different menu item view.
                $segments[] = $query['id'];
            }
            unset($query['id']);
        }

        if (isset($query['layout']))
        {
            if (isset($item->query['layout']) && ($item->query['layout'] == $query['layout'])) {
                // We can take it out as it's already available in the menu item URL we are accessing.
                unset($query['layout']);
            }
            else
            {
                if ($query['layout'] == 'default') {
                    // We can safely remove it as default is the default layout.
                    unset($query['layout']);
                }
            }
        }

        return $segments;
    }

    public function parseRoute($segments)
    {
        $vars = array();

        //Get the active menu item
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();

        // Count route segments
        $count = count($segments);

        //Standard routing for articles
        if (!isset($item))
        {
            $vars['view'] = (count($segments) === 1) ? 'category' : $segments[0];
            $vars['id']   = $segments[$count - 1];
            return $vars;
        }

        //Handle View and Identifier
        switch ($item->query['view'])
        {
            case 'section':

                switch ($count)
                {
                    case 1:
                        $vars['view'] = 'category';

                        if (isset($item->query['layout']) && $item->query['layout'] == 'blog') {
                            $vars['layout'] = 'blog';
                        }
                        break;
                    case 2:
                        $vars['view']  = 'article';
                        $vars['catid'] = $segments[$count - 2];
                        break;
                }
                $vars['id'] = $segments[$count - 1];
                break;

            case 'category':
                $vars['id']   = $segments[$count - 1];
                $vars['view'] = 'article';
                break;

            case 'articles':
                if (count($segments) === 1) {
                    // Accessing a
                    $vars['id']   = $segments[0];
                    $vars['view'] = 'article';
                }
                break;

            case 'article':
                $vars['id']   = $segments[$count - 1];
                $vars['view'] = 'article';
                break;
        }

        return $vars;
    }
}



