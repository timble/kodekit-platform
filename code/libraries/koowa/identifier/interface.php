<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Identifier
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Identifier interface
 *
 * Wraps identifiers of the form [application::]type.component.[.path].name
 * in an object, providing public accessors and methods for derived formats
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Identifier
 */
interface KIdentifierInterface extends Serializable 
{ 
    /**
     * Returns an identifier object. 
	 * 
	 * Accepts various types of parameters and returns a valid identifier. Parameters can either be an 
	 * object that implements KObjectIdentifiable, or a KIdentifierInterface, or valid identifier 
	 * string. Function will also check for identifier mappings and return the mapped identifier.
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that 
	 *                  implements KIdentifierInterface or valid identifier string
	 * @return KIdentifier
	 * @see __construct()
	 */
	public static function identify($identifier);

    /**
     * Formats the indentifier as a [application::]type.component.[.path].name string
     *
     * @return string
     */
    public function __toString();
}