<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Default controller dispatcher
 * 
 * The default dispatcher mplements a signleton. After instantiation the object can
 * be access using the mapped lib.koowa.dispatcher identifier.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Dispatcher
 */

class KDispatcherDefault extends KDispatcherAbstract 
{ 
    /**
     * Force creation of a singleton
     *
     * @return KDispatcherDefault
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
            KFactory::map('lib.koowa.dispatcher', $config->identifier);
        }
        
        return $instance;
    }
}