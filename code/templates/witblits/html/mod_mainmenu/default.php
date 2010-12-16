<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
require_once(JModuleHelper::getLayoutPath('mod_mainmenu', 'helper'));

if ( ! defined('modNewMainMenuXMLCallbackDefined') )
{
function modNewMainMenuXMLCallback(&$node, $args)
{
	$i		= '';
	$user	= JFactory::getUser();
	$menu	= JSite::getMenu();
	$active	= $menu->getActive();
	$path	= isset($active) ? array_reverse($active->tree) : null;

	if (($args['end']) && ($node->attributes('level') >= $args['end']))
	{
		$children = $node->children();
		foreach ($node->children() as $child)
		{
			if ($child->name() == 'ul') {
				$node->removeChild($child);
			}
		}
	}

	if ($node->name() == 'ul') {  
		foreach ($node->children() as $child) {
		
			
			if ($child->attributes('access') > $user->get('aid', 0)) {
				$node->removeChild($child);
			}
			if ($i == 0) {
						$child->addAttribute('class', ' first');
			}
	    	if($i == count($node->children())-1) {
	    		$child->addAttribute('class', $node->attributes('class').' last');
	    	}
	    	//$child->addAttribute('class', $node->attributes('class').' level'.$child->_attributes['level']);
	    	$i++;
	 	}
	}

	if (($node->name() == 'li') && isset($node->ul)) {
		$node->addAttribute('class', 'parent');
		//$node->addAttribute('class', $node->attributes('class').' level'.$node->_attributes['level']);
	}

	if (isset($path) && in_array($node->attributes('id'), $path))
	{
		if ($node->attributes('class')) {
			$node->addAttribute('class', $node->attributes('class').' active');
		} else {
			$node->addAttribute('class', 'active');
		}
	}
	else
	{
		if (isset($args['children']) && !$args['children'])
		{
			$children = $node->children();
			foreach ($node->children() as $child)
			{
				if ($child->name() == 'ul') {
					$node->removeChild($child);
				}
			}
		}
	}

	if (($node->name() == 'li') && ($id = $node->attributes('id'))) {
	
		if ($node->attributes('class')) {
			$node->addAttribute('class', $node->attributes('class').' item'.$id);
		} else {
			$node->addAttribute('class', 'item'.$id);
		}
		foreach($node->children() as $child){
			if($child->_level == '2' && $child->_name == 'a'){
				$child->addAttribute('class', $node->attributes('class').' top-level');
			}
		}
	}

	if (isset($path) && $node->attributes('id') == $path[0]) {
		$node->addAttribute('id', 'current');
	} else {
		$node->removeAttribute('id');
	}
	$node->removeAttribute('level');
	$node->removeAttribute('access');
}
	define('modNewMainMenuXMLCallbackDefined', true);
}

modNewMainMenuHelper::render($params, 'modNewMainMenuXMLCallback');