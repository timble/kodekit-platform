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
class ComDefaultEventDispatcher extends KEventDispatcher implements KObjectInstantiatable
{
 	/**
     * Force creation of a singleton
     *
     * @param 	object	An optional KConfig object with configuration options
     * @param 	object	A KFactoryInterface object
     * @return ComDefaultEventDispatcher
     */
    public static function getInstance(KConfig $config, KFactoryInterface $factory)
    { 
       // Check if an instance with this identifier already exists or not
        if (!$factory->exists($config->identifier))
        {
            //Create the singleton
            $classname = $config->identifier->classname;
            $instance  = new $classname($config);
            $factory->set($config->identifier, $instance);
            
            //Add the factory map to allow easy access to the singleton
            KIdentifier::map('koowa:event.dispatcher', $config->identifier);
        }
        
        return $factory->get($config->identifier);
    }
}