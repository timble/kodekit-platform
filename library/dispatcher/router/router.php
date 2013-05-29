<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Router
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Default Router Class
 *
 * Provides route building and parsing functionality
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Router
 */
class DispatcherRouter extends Object implements DispatcherRouterInterface, ObjectSingleton
{
    /**
     * Function to convert a route to an internal URI
     *
     * @param   HttpUrl  $url  The url.
     * @return  boolean
     */
    public function parse(HttpUrl $url)
    {
        return true;
    }

    /**
     * Function to convert an internal URI to a route
     *
     * @param	HttpUrl   $url	The internal URL
     * @return	boolean
     */
    public function build(HttpUrl $url)
    {
        // Build the url : mysite/route/index.php?var=x
        return true;
    }
}
