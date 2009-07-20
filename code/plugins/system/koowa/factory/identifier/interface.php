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
 * Wraps identifiers of the form [application::]type.component.[.path].name
 * in an object, providing public accessors and methods for derived formats
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Identifier
 */
interface KFactoryIdentifierInterface
{
	/**
	 * Constructor
	 *
	 * @param	string|object	Identifier string or object in [application::]type.component.[.path].name format
	 */
	public function __construct($identifier);

	/**
	 * Formats the indentifier as a [application::]type.component.[.path].name string
	 *
	 * @return string
	 */
	public function __toString();
}