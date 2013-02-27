<?php
/**
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
     * @param   KHttpUrl  $url  The url.
     * @return  boolean
     */
	public function parse(KHttpUrl $uri);

    /**
     * Function to convert an internal URI to a route
     *
     * @param	KhttpUrl   $url	The internal URL
     * @return	boolean
     */
	public function build(KHttpUrl $url);
}
