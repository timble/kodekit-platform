<?php
/**
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Application Dispatcher Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 */
class DispatcherApplication extends DispatcherAbstract implements ObjectInstantiable
{
    /**
     * Force creation of a singleton
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return DispatcherApplication
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists
        if (!$manager->isRegistered('application'))
        {
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->registerAlias('application', $config->object_identifier);
        }

        return $manager->getObject('application');
    }

    /**
     * Dispatch the request
     *
     * Dispatch to a controller internally. Functions makes an internal sub-request, based on the information in
     * the request and passing along the context.
     *
     * @param   CommandContext	$context A command context object
     * @return	mixed
     */
    protected function _actionDispatch(CommandContext $context)
    {
        //Send the response
        $this->send($context);
    }

    /**
     * Send the response
     *
     * @param CommandContext $context	A command context object
     */
    public function _actionSend(CommandContext $context)
    {
        $context->response->send();
        exit(0);
    }
}