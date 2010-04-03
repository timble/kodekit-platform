<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Identifier
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Identifier interface
 *
 * Wraps identifiers of the form [application::]type.component.[.path].name
 * in an object, providing public accessors and methods for derived formats
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Identifier
 */
interface KIdentifierInterface
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