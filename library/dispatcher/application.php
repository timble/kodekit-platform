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
class DispatcherApplication extends DispatcherAbstract implements ObjectInstantiatable
{
    /**
     * Constructor.
     *
     * @param 	object 	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
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
     * @param 	object 	An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
    	$config->append(array(
        	'component' => $this->getIdentifier()->package,
         ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	ObjectConfig                  $config	  A ObjectConfig object with configuration options
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
            $manager->register($config->object_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->setAlias('application', $config->object_identifier);
        }

        return $manager->get('application');
    }

    /**
     * Method to get a dispatcher object
     *
     * @throws	\UnexpectedValueException	If the controller doesn't implement the ControllerInterface
     * @return	ControllerAbstract
     */
    public function getComponent()
    {
        if(!($this->_controller instanceof DispatcherInterface))
        {
            $this->_controller = $this->getController();

            if(!$this->_controller instanceof DispatcherInterface)
            {
                throw new \UnexpectedValueException(
                    'Dispatcher: '.get_class($this->_controller).' does not implement DispatcherInterface'
                );
            }
        }

        return $this->_controller;
    }

    /**
     * Method to set a dispatcher object
     *
     * @param	mixed	$component  An object that implements ControllerInterface, ObjectIdentifier object
     * 					            or valid identifier string
     * @return	DispatcherAbstract
     */
    public function setComponent($component, $config = array())
    {
        if(!($component instanceof DispatcherInterface))
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
     * @param CommandContext $context	A command context object
     */
    protected function _actionDispatch(CommandContext $context)
    {
        $this->getComponent()->dispatch($context);
    }

    /**
     * Send the response to the client
     *
     * @param CommandContext $context	A command context object
     */
    public function _actionSend(CommandContext $context)
    {
        $context->response->send();
        exit(0);
    }
}