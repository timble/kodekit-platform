<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Dispatcher Router
 *
 * Provides route buidling and parsing functionality
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 */
interface DispatcherRouterInterface
{
    /**
     * Function to convert a route to an internal URI
     *
     * @param   HttpUrlInterface  $url  The url.
     * @return  boolean
     */
	public function parse(HttpUrlInterface $uri);

    /**
     * Function to convert an internal URI to a route
     *
     * @param	HttpUrlInterface   $url	The internal URL
     * @return	boolean
     */
	public function build(HttpUrlInterface $url);
}
