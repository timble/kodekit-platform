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
 * Item views are people/id-firstname_lastname, offices/id-officealias, departments/id-departmentalias
 */
function ProfilesBuildRoute(&$query)
{
	$segments = array();
	if(array_key_exists('view', $query))
	{
		$segments[0] = $query['view'];

		if(array_key_exists('id', $query)){
			$segments[1] = $query['id'];
			$segments[0] = KInflector::pluralize($segments[0]);
		}

		unset($query['view']);
		unset($query['id']);
	}

	return $segments;
}

function ProfilesParseRoute($segments)
{
	if(isset($segments[0]))
	{
		$vars['view'] = $segments[0];
		if(isset($segments[1])) {
			$vars['id'] = (int) $segments[1];
			$vars['view'] = KInflector::singularize($vars['view']);
		}
	}
	return $vars;
}
