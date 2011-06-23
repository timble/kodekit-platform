<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
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

function UsersBuildRoute(&$query)
{
	$segments = array();

	if(isset($query['view']))
	{
		if(empty($query['Itemid'])) {
			$segments[] = $query['view'];
		} else {
			$menu = &JSite::getMenu();
			$menuItem = &$menu->getItem( $query['Itemid'] );
			if(!isset($menuItem->query['view']) || $menuItem->query['view'] != $query['view']) {
				$segments[] = $query['view'];
			}
		}
		unset($query['view']);
	}
	return $segments;
}

function UsersParseRoute($segments)
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
