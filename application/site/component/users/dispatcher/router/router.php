<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;

/**
 * Users Router
 *
 * @author      Gergo Erdosi <http://github.com/gergoerdosi>
 * @package     Kodekit\Platform\Users
 */

class DispatcherRouter extends Library\DispatcherRouter
{
    public function build(Library\HttpUrlInterface $url)
    {
        $segments = array();
        $query    = &$url->query;

        if(isset($query['view']))
        {
            if(!empty($query['Itemid']))
            {
                $page = $this->getObject('pages')->getPage($query['Itemid'] );
                if(!isset($page->getLink()->query['view']) || $page->getLink()->query['view'] != $query['view']) {
                    $segments[] = $query['view'];
                }
            }
            else $segments[] = $query['view'];

            unset($query['view']);
        }

        return $segments;
    }

    public function parse(Library\HttpUrlInterface $url)
    {
        $vars = array();
        $path = &$url->path;

        $count = count($path);
        if(!empty($count)) {
            $vars['view'] = $path[0];
        }

        if($count > 1) {
            $vars['id'] = $path[$count - 1];
        }

        return $vars;
    }
}


