<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Identifier
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Identifier interface
 *
 * Wraps identifiers of the form application::extension.component.type[[.path].name]
 * in an object, providing public accessors and methods for derived formats
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Identifier
 */
interface KFactoryIdentifierInterface
{
	/**
	 * Returns the classname for the identifier
	 *
	 * @return string
	 */
	public function getClassName();
	
	/**
	 * Converts the identifer back to a string
	 *
	 * @return string
	 */
	public function __toString();
}