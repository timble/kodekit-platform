<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Table
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Default Database Table Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Table
 */
class KDatabaseTableDefault extends KDatabaseTableAbstract implements KObjectInstantiatable
{
	/**
     * Force creation of a singleton
     *
     * @return KDatabaseTableDefault
     */
    public static function getInstance($config = array())
    {
        static $instances;
        
        // For fallbacks we need to store instancess depending on classname
        if ($instances === NULL) {
            $instances = array();
        }

        // Check if an instance with this identifier already exists or not
        $instance = (string)$config->identifier;
        if (!isset($instances[$instance]))
        {
            //Create the singleton
            $classname = $config->identifier->classname;
            $instances[$instance] = new $classname($config);
        }
        
        return $instances[$instance];
    }
}