<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @subpackage  Router
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Router Class
 *
 * Provides route buidling and parsing functionality
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Router
 */
interface KDispatcherRouterInterface
{
	/**
	 * Function to convert a route to an internal URI
     *
     * @param   JURI  $uri  The uri.
     * @return  array
	 */
	public function parse($uri);

	/**
	 * Function to convert an internal URI to a route
	 *
	 * @param	string	$string	The internal URL
	 * @return	string	The absolute search engine friendly URL
	 */
	public function build($url);

	/**
	 * Set a router variable, creating it if it doesn't exist
	 *
	 * @param	string  $key    The name of the variable
	 * @param	mixed   $value  The value of the variable
	 * @param	boolean $create If True, the variable will be created if it doesn't exist yet
     * @return \KDispatcherRouterInterface
 	 */
	public function setVar($key, $value, $create = true);

	/**
	 * Set the router variable array
	 *
	 * @param	array   $vars   An associative array with variables
	 * @param	boolean $create If True, the array will be merged instead of overwritten
     * @return \KDispatcherRouterInterface
 	 */
	public function setVars($vars = array(), $merge = true);

	/**
	 * Get a router variable
	 *
	 * @param	string $key   The name of the variable
	 * @return  mixed  Value of the variable
 	 */
	public function getVar($key);

	/**
	 * Get the router variable array
	 *
	 * @return  array An associative array of router variables
 	 */
	public function getVars();

	/**
	 * Attach a build rule
	 *
	 * @param   callback $callback The function to be called.
 	 */
	public function attachBuildRule($callback);

	/**
	 * Attach a parse rule
	 *
	 * @param   callback $callback The function to be called.
 	 */
	public function attachParseRule($callback);
}
