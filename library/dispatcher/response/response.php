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
 * Dispatcher Response Singleton
 *
 * Force the user object to a singleton with identifier alias 'response'.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherResponse extends DispatcherResponseAbstract implements ObjectInstantiable, ObjectSingleton
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
        if (!$manager->isRegistered('dispatcher.response'))
        {
            //Create the singleton
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the object alias to allow easy access to the singleton
            $manager->registerAlias('dispatcher.response', $config->object_identifier);
            $manager->registerAlias('response', 'dispatcher.response');
        }

        return $manager->getObject('dispatcher.response');
    }
}