<?php
/**
 * @version     $Id: mysqli.php 3702 2011-07-18 21:55:44Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Default Event Dispatcher
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultEventDispatcher extends KEventDispatcher implements KServiceInstantiatable
{
 	/**
     * Force creation of a singleton
     *
     * @param 	object	An optional KConfig object with configuration options
     * @param 	object	A KServiceInterface object
     * @return ComDefaultEventDispatcher
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

            //Add the factory map to allow easy access to the singleton
            $container->setAlias('koowa:event.dispatcher', $config->service_identifier);
        }

        return $container->get($config->service_identifier);
    }
}