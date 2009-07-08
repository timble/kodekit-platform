<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Identifier
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
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
	 * Converts the identifer back to a string
	 *
	 * @return string
	 */
	public function __toString();

	/**
	 * Returns the classname for the identifier
	 *
	 * @return string
	 */
	public function getClassName();
}