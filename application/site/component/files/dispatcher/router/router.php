<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Files;

use Kodekit\Library;

/**
 * Router
 *
 * @author   Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Platform\Files
 */
class DispatcherRouter extends Library\DispatcherRouter
{
    public function build(Library\HttpUrlInterface $url)
    {
        $segments = array();
        $query    = &$url->query;

        if (isset($query['Itemid'])) {
            $page = $this->getObject('pages')->getPage($query['Itemid']);
        } else {
            $page = $this->getObject('pages')->getActive();
        }

        $menu_query = $page->getLink()->query;

        if (isset($query['view']) && $query['view'] === 'file') {
            $segments[] = 'file';
        }

        if (isset($query['layout']) && isset($menu_query['layout']) && $query['layout'] === $menu_query['layout']) {
            unset($query['layout']);
        }

        if (isset($query['folder']))
        {
            if (empty($menu_query['folder'])) {
                $segments[] = str_replace('%2F', '/', $query['folder']);
            }
            else if ($query['folder'] == $menu_query['folder']) {
                // do nothing
            }
            else if (strpos($query['folder'], $menu_query['folder']) === 0) {
                $segments[] = str_replace($menu_query['folder'].'/', '', $query['folder']);
            }
        }

        if (isset($query['name']))
        {
            $segments[] = $query['name'];
        }

        unset($query['view']);
        unset($query['folder']);
        unset($query['name']);

        return $segments;
    }

    public function parse(Library\HttpUrlInterface $url)
    {
        $vars = array();
        $path = &$url->path;

        $page  = $this->getObject('pages')->getActive();
        $query = $page->getLink()->query;

        if ($path[0] === 'file')
        { // file view
            $vars['view']    = array_shift($path);
            $vars['name']    = array_pop($path);
            $vars['folder']  = $query['folder'] ? $query['folder'] : '';
            $vars['folder'] .= !empty($path) ? '/'.implode('/', $path) : '';
        }
        else
        { // directory view
            $vars['view']   = 'directory';
            $vars['layout'] = $query['layout'];
            $vars['folder'] = $query['folder'].'/'.implode('/', $path);
        }

        $vars['folder'] = str_replace('%2E', '.', $vars['folder']);
        $vars['layout'] = $query['layout'];

        return $vars;
    }
}