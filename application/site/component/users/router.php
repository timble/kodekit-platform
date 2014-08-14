<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Users Router
 *
 * @author      Gergo Erdosi <http://github.com/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Users
 */

class UsersRouter extends Library\DispatcherRouter
{
    public function build(Library\HttpUrlInterface $url)
    {
        $segments = array();
        $query    = &$url->query;

        if(isset($query['view']))
        {
            if(!empty($query['Itemid']))
            {
                $page = $this->getObject('application.pages')->getPage($query['Itemid'] );
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


