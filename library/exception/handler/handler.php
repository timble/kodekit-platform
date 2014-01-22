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
 * Exception Handler
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Exception
 */
class ExceptionHandler extends ExceptionHandlerAbstract implements ObjectInstantiable, ObjectSingleton
{
    /**
     * Force creation of a singleton
     *
     * @param  ObjectConfig             $config	  A ObjectConfig object with configuration options
     * @param  ObjectManagerInterface	$manager  A ObjectInterface object
     * @return DispatcherRequest
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        if (!$manager->isRegistered('exception.handler'))
        {
            $class    = $manager->getClass($config->object_identifier);
            $instance = new $class($config);
            $manager->setObject($config->object_identifier, $instance);

            $manager->registerAlias($config->object_identifier, 'exception.handler');
        }

        return $manager->getObject('exception.handler');
    }
}