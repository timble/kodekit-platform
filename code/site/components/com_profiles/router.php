<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * List views are people/, offices/, departments/
 * Item views are people/id-firstname_lastname, offices/officeslug, departments/departmentslug
 */
function ProfilesBuildRoute(&$query)
{
	$segments = array();
	if(array_key_exists('view', $query))
	{
		if(array_key_exists('slug', $query)){
			$segments[0] = $query['slug'];
		}

		unset($query['view']);
		unset($query['slug']);
	}

	return $segments;
}

function ProfilesParseRoute($segments)
{
	$item = JSite::getMenu()->getActive();
	
	//We are using the alias, circonvent the auto-segment decoding
	$segments = str_replace(':', '-', $segments);

	$vars = array();
	$vars['view'] = $item->query['view'];
	
	if(count($segments) > 0)
	{
		$vars['view'] = KInflector::singularize($item->query['view']);
        
		if($id = (int) $segments[0]) {
			$vars['id'] = $id;
		} else {
			$vars['slug'] = $segments[0];
		}
	}
	
	return $vars;
}
