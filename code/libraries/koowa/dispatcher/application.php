<?php
/**
 * @version		$Id: abstract.php 5015 2012-10-23 15:06:14Z johanjanssens $
 * @package		Koowa_Dispatcher
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Application Dispatcher Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 */
class KDispatcherApplication extends KDispatcherAbstract implements KServiceInstantiatable
{
	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);


	}

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
        	'controller' => $this->getIdentifier()->package,
         ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KServiceInterface object
     * @return KDispatcherDefault
     */
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        // Check if an instance with this identifier already exists or not
        if (!$container->has('application'))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $container->setAlias('application', $config->service_identifier);
        }

        return $container->get('application');
    }


    /**
     * Dispatch the request
     *
     * @param KCommandContext $context	A command context object
     */
    protected function _actionDispatch(KCommandContext $context)
    {
        $this->getController()->dispatch($context);

        //Send the response
        $this->send();
    }

    /**
     * Send the response to the client
     *
     * @param KCommandContext $context	A command context object
     */
    protected function _actionSend(KCommandContext $context)
    {
        $context->response->send();
        exit(0);
    }
}