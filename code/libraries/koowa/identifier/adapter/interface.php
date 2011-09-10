<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Identifier
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Identifier Adapter Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Identifier
 * @subpackage 	Adapter
 */
interface KIdentifierAdapterInterface
{
	/**
	 * Get the classname based on an identifier
	 *
	 * @param 	object 			An Identifier object - [application::]type.package.[.path].name
	 * @return 	string|false 	Returns the class on success, returns FALSE on failure
	 */
	public function findClass(KIdentifier $identifier);
	
	 /**
     * Get the path based on an identifier
     *
     * @param  object   An Identifier object - [application::]type.package.[.path].name
     * @return string	Returns the path
     */
    public function findPath(KIdentifier $identifier);
	
	/**
	 * Get the type
	 *
	 * @return string	Returns the type
	 */
	public function getType();
}