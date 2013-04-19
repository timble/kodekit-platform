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
class DispatcherRouter extends Object implements DispatcherRouterInterface, ObjectInstantiatable
{
    /**
     * Force creation of a singleton
     *
     * @param 	Config                  $config	  A Config object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  DispatcherRouter
     */
    public static function getInstance(Config $config, ObjectManagerInterface $manager)
    {
        if (!$manager->has($config->object_identifier))
        {
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->object_identifier, $instance);
        }

        return $manager->get($config->object_identifier);
    }

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
