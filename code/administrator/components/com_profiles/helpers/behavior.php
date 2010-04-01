<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Behavior Helper
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @author		Stian Didriksen <stian@ninjaforge.com>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class ComProfilesHelperBehavior extends KTemplateHelperBehavior
{
	/**
	 * Helper for creating DOM element ids used by javascript behaviors
	 *
	 * If no type and package identifiers were supplied,
	 * uses the current option $_GET variable, and changing com_foo_bar to com-foo_bar.
	 * '-' are used as separators, and in our javascript used to parse identifier strings.
	 * If a form got the id com-foo_bar-people, 
	 * then we can assume that the toolbar will have the id toolbar-people,
	 *
	 * @author Stian Didriksen <stian@ninjaforge.com>
	 * @param  array | int $parts
	 * @return string
	 */
	public function id ( $parts = array() )
	{
		if(!is_array($parts) && is_int($parts)) $parts['id'] = (int) $parts;

		// If we pass a string, set $parts back as an array in order to proceed.
		if(!is_array($parts)) settype($parts, 'array');
		
		// Set the defaults, if needed
		$defaults = array();
		if ( !isset( $parts['type.package'] ) )
		{
			// We only want to replace the first underscore, not the rest.
			$defaults['type_package'] = str_replace('com_', 'com-', KRequest::get('get.option', 'cmd'));
		}
		if ( !isset( $parts['view'] ) )
		{
			$view = KRequest::get('get.view', 'cmd');
			
			// The view part always needs to be plural to allow ajax BREAD.
			if ( KInflector::isSingular($view) ) $view = KInflector::pluralize($view);
			
			$defaults['view'] = $view;
		}
		
		if ( !isset( $parts['id'] ) && KRequest::has('get.id', 'int') )
		{
			$defaults['id'] = KRequest::get('get.id', 'int');
		}

		// Filter away parts that are unset on purpose using a null value, or a negative boolean.
		return implode('-', array_filter(array_merge($defaults, $parts)));
	}
	
	/**
	 * Setter and getter for the current active javascript framework in use by template behaviors.
	 *
	 * Also useful if you got multiple sets of inline javascripts 
	 * that you toggle based on the active framework
	 *
	 * @author	Stian Didriksen <stian@ninjaforge.com>
	 * @param	boolean | string $define
	 * @return	string
	 */
	public function framework($define = false)
	{
		static $framework;
		
		if ( !$framework )	$framework = $define ? $define : 'mootools11';
		else if ( $define )	$framework = $define;
		return $framework;
	}
}