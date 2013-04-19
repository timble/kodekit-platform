<?php
/**
 * @package     Koowa_Database
 * @subpackage  Table
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Default Database Table Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Table
 */
class DatabaseTableDefault extends DatabaseTableAbstract implements ObjectInstantiatable
{
	/**
     * Force creation of a singleton
     *
     * @param 	ConfigI                 $config	  A Config object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return DatabaseTableDefault
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
}