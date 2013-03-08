<?php
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Contacts Router Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */

class ComContactsRouter extends ComBaseRouter
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
            if(isset($query['category'])) {
                $segments[] = $query['category'];
            }

            if(isset($query['id'])) {
                $segments[] = $query['id'];
            }
        }

        if($view == 'contacts')
        {
            if(isset($query['id'])) {
                $segments[] = $query['id'];
            }
        }

        if(isset($query['view']) && $query['view'] == 'message') {
            $segments[] = 'message';
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
            if ($count)
            {
                $count--;
                $segment = array_shift( $segments );

                $vars['category'] = $segment;
                $vars['view'] = 'contacts';
            }

            if ($count)
            {
                $count--;
                $segment = array_shift( $segments) ;

                $vars['id'] = $segment;
                $vars['view'] = 'contact';
            }
        }

        if($view == 'contacts')
        {
            $segment = array_shift( $segments) ;

            $vars['id'] = $segment;
            $vars['view'] = 'contact';
        }

        if(count($segments) && $segments[0] == 'message') {
            $vars['view'] = 'message';
        }

        return $vars;
    }
}

