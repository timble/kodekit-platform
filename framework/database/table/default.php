<?php
/**
 * @package     Koowa_Database
 * @subpackage  Table
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Default Database Table Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Table
 */
class DatabaseTableDefault extends DatabaseTableAbstract implements ServiceInstantiatable
{
	/**
     * Force creation of a singleton
     *
     * @param 	ConfigI                 $config	  A Config object with configuration options
     * @param 	ServiceManagerInterface	$manager  A ServiceInterface object
     * @return DatabaseTableDefault
     */
    public static function getInstance(Config $config, ServiceManagerInterface $manager)
    {
        if (!$manager->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }
}