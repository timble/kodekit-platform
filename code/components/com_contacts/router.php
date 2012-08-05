<?php
/**
 * @version		$Id: router.php 3546 2012-04-02 19:05:32Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Contacts Router Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */

class ComContactsRouter extends ComDefaultRouter
{
    public function buildRoute(&$query)
    {
        $segments = array();

        if(isset($query['Itemid'])) {
            $page = JFactory::getApplication()->getPages()->find($query['Itemid']);
        } else {
            $page = JFactory::getApplication()->getPages()->getActive();
        }

        $view = $page->link->query['view'];

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

        unset($query['category']);
        unset($query['id']);
        unset($query['view']);

        return $segments;
    }

    public function parseRoute($segments)
    {
        $vars = array();

        $page = JFactory::getApplication()->getPages()->getActive();

        $view  = $page->link->query['view'];
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

        return $vars;
    }
}

