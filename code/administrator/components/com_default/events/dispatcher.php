<?php
/**
 * @version     $Id: mysqli.php 3702 2011-07-18 21:55:44Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Default Event Dispatcher
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultEventDispatcher extends KEventDispatcher
{
	/**
     * Force creation of a singleton
     *
     * @return KDatabaseTableDefault
     */
    public static function instantiate($config = array())
    {
        static $instance;
        
        if ($instance === NULL) 
        {
            //Create the singleton
            $classname = $config->identifier->classname;
            $instance = new $classname($config);
            
            //Add the factory map to allow easy access to the singleton
            KFactory::map('koowa:event.dispatcher', $config->identifier);
            
        }
        
        return $instance;
    }
}