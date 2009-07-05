<?php
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
