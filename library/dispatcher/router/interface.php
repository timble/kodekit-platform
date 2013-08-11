<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Dispatcher Router
 *
 * Provides route buidling and parsing functionality
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
interface DispatcherRouterInterface
{
    /**
     * Function to convert a route to an internal URI
     *
     * @param   HttpUrl  $url  The url.
     * @return  boolean
     */
	public function parse(HttpUrl $uri);

    /**
     * Function to convert an internal URI to a route
     *
     * @param	HttpUrl   $url	The internal URL
     * @return	boolean
     */
	public function build(HttpUrl $url);
}
