<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Router
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Default Router Class
 *
 * Provides route building and parsing functionality
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Router
 */
class KDispatcherRouter extends KObject implements KDispatcherRouterInterface, KServiceInstantiatable
{
    /**
     * Force creation of a singleton
     *
     * @param 	KConfigInterface            $config	  A KConfig object with configuration options
     * @param 	KServiceManagerInterface	$manager  A KServiceInterface object
     * @return KDispatcherSessionDefault
     */
    public static function getInstance(KConfigInterface $config, KServiceManagerInterface $manager)
    {
        if (!$manager->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

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
