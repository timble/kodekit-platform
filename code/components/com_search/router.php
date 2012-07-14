<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Router
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */

class ComSearchRouter extends ComDefaultRouter
{
    public function buildRoute(&$query)
    {
        $segments = array();

        if(isset($query['view'])) {
            unset($query['view']);
        }

        return $segments;
    }

    public function parseRoute($segments)
    {
        $vars = array();

        return $vars;
    }
}

