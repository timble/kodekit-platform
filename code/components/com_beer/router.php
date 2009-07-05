<?php
/**
* @version		$Id: router.php 11678 2009-03-08 20:48:42Z willebil $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

function BeerBuildRoute(&$query)
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

function BeerParseRoute($segments)
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
