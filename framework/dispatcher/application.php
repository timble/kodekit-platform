<?php
/**
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

        //Set the component
        $this->setComponent($config->component);
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
        	'component' => $this->getIdentifier()->package,
         ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	KConfigInterface            $config	  A KConfig object with configuration options
     * @param 	KServiceManagerInterface	$manager  A KServiceInterface object
     * @return KDispatcherApplication
     */
    public static function getInstance(KConfigInterface $config, KServiceManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists
        if (!$manager->has('application'))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->setAlias('application', $config->service_identifier);
        }

        return $manager->get('application');
    }

    /**
     * Method to get a dispatcher object
     *
     * @throws	\UnexpectedValueException	If the controller doesn't implement the KControllerInterface
     * @return	KControllerAbstract
     */
    public function getComponent()
    {
        if(!($this->_controller instanceof KDispatcherInterface))
        {
            $this->_controller = $this->getController();

            if(!$this->_controller instanceof KDispatcherInterface)
            {
                throw new \UnexpectedValueException(
                    'Dispatcher: '.get_class($this->_controller).' does not implement KDispatcherInterface'
                );
            }
        }

        return $this->_controller;
    }

    /**
     * Method to set a dispatcher object
     *
     * @param	mixed	$component  An object that implements KControllerInterface, KServiceIdentifier object
     * 					            or valid identifier string
     * @return	KDispatcherAbstract
     */
    public function setComponent($component, $config = array())
    {
        if(!($component instanceof KDispatcherInterface))
        {
            if(is_string($component) && strpos($component, '.') === false )
            {
                $identifier			 = clone $this->getIdentifier();
                $identifier->package = $component;
            }
            else $identifier = $this->getIdentifier($component);

            $component = $identifier;
        }

        $this->setController($component, $config);

        return $this;
    }

    /**
     * Dispatch the request
     *
     * @param KCommandContext $context	A command context object
     */
    protected function _actionDispatch(KCommandContext $context)
    {
        $this->getComponent()->dispatch($context);
    }

    /**
     * Send the response to the client
     *
     * @param KCommandContext $context	A command context object
     */
    public function _actionSend(KCommandContext $context)
    {
        $context->response->send();
        exit(0);
    }
}