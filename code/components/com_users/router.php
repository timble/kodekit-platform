<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Users Router
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */

class ComUsersRouter extends ComDefaultRouter
{
    public function buildRoute(&$query)
    {
        $segments = array();

        if(isset($query['view']))
        {
            if(!empty($query['Itemid']))
            {
                $menu = JFactory::getApplication()->getMenu()->getItem( $query['Itemid'] );
                if(!isset($menu->query['view']) || $menu->query['view'] != $query['view']) {
                    $segments[] = $query['view'];
                }
            }
            else $segments[] = $query['view'];

            unset($query['view']);
        }
        return $segments;
    }

    public function parseRoute($segments)
    {
        $vars = array();

        $count = count($segments);
        if(!empty($count)) {
            $vars['view'] = $segments[0];
        }

        if($count > 1) {
            $vars['id']    = $segments[$count - 1];
        }

        return $vars;
    }
}


