<?php
/**
 * @version     $Id: dispatcher.php 4711 2012-07-13 01:15:13Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Router
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultRouter extends KObject implements KServiceInstantiatable
{
	/**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KServiceInterface object
     * @return KDispatcherDefault
     */
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    { 
       // Check if an instance with this identifier already exists or not
        if (!$container->has($config->service_identifier))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);
        }
        
        return $container->get($config->service_identifier);
    }

    /**
     * Build the route
     *
     * @param	array	An array of URL arguments
     * @return	array	The URL arguments to use to assemble the subsequent URL.
     */
    public function buildRoute(&$query)
    {
        $segments = array();
        return $segments;
    }

    /**
     * Parse the segments of a URL.
     *
     * @param	array	The segments of the URL to parse.
     * @return	array	The URL attributes to be used by the application.
     */
    public function parseRoute($segments)
    {
        $vars = array();
        return $vars;
    }
}