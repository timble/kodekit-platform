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
 * Provides route building and parsing functionality
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Router
 */
abstract class KDispatcherRouterAbstract extends KObject implements KDispatcherRouterInterface
{
	/**
	 * Function to convert a route to an internal URI
     *
     * @param   KHttpUrl  $url  The url.
     * @return  boolean
	 */
	public function parse(KHttpUrl $url)
	{
		$this->_parseRoute($url);
	 	return true;
	}

	/**
	 * Function to convert an internal URI to a route
	 *
	 * @param	KhttpUrl   $url	The internal URL
	 * @return	boolean
	 */
	public function build(KHttpUrl $url)
	{
		// Build the url : mysite/route/index.php?var=x
		$this->_buildRoute($url);
		return true;
	}
}
