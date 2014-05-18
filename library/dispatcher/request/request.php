<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Dispatcher Request Singleton
 *
 * Force the user object to a singleton with identifier with alias 'request'.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherRequest extends DispatcherRequestAbstract implements ObjectInstantiable, ObjectSingleton
{
    /**
     * Force creation of a singleton
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return DispatcherRequest
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        if (!$manager->isRegistered('dispatcher.request'))
        {
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->registerAlias('dispatcher.request', $config->object_identifier);
            $manager->registerAlias('request', 'dispatcher.request');
        }

        return $manager->getObject('dispatcher.request');
    }
}