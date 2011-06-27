<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Loader Adapter Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 */
interface KLoaderAdapterInterface
{
	/**
	 * Get the path based on a class name or an identifier
	 *
	 * @param string  The class name
	 * @return boolean Return TRUE on success, FALSE on failure
	 */
	public function path($class);
	
	/**
	 * Get the class prefix
	 *
	 * @return string	Returns the class prefix
	 */
	public function getPrefix();
	
	/**
	 * Get the base path
	 *
	 * @return string	Returns the base path
	 */
	public function getBasepath();
}