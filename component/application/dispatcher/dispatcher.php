<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Http Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class Dispatcher extends Library\DispatcherAbstract implements Library\ObjectInstantiable
{
    /**
     * Component name or identifier
     *
     * @var	string|object
     */
    protected $_component;

    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config	An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the component
        $this->_component = $config->component;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	Library\ObjectConfig    $config  An optional Library\ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'dispatched' => true,
            'controller' => 'document',
            'component'  => '',
            'request'    => array('
                base_url'   => '/'
            ),
            'title'      => 'Application',
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	Library\ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	Library\ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  Dispatcher
     */
    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists
        if (!$manager->isRegistered('application'))
        {
            $instance  = new static($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->registerAlias($config->object_identifier, 'application');
        }

        return $manager->getObject('application');
    }

    /**
     * Get the application title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfig()->title;
    }

    /**
     * Get the application router.
     *
     * @param  array $options 	An optional associative array of configuration options.
     * @return	Dispatcher
     */
    public function getRouter(array $options = array())
    {
        return $this->getObject('com:application.router', $options);
    }

    /**
     * Method to get a component dispatcher object
     *
     * @throws	\UnexpectedValueException	If the dispatcher doesn't implement the DispatcherInterface
     * @return	Library\DispatcherInterface
     */
    public function getComponent()
    {
        if(!($this->_component instanceof Library\DispatcherInterface))
        {
            //Make sure we have a controller identifier
            if(!($this->_component instanceof Library\ObjectIdentifier)) {
                $this->setComponent($this->_component);
            }

            $config = array(
                'request' 	 => $this->getRequest(),
                'user'       => $this->getUser(),
                'response'   => $this->getResponse(),
                'dispatched' => true
            );

            $this->_component = $this->getObject($this->_component, $config);

            //Make sure the controller implements ControllerInterface
            if(!$this->_component instanceof Library\DispatcherInterface)
            {
                throw new \UnexpectedValueException(
                    'Dispatcher: '.get_class($this->_controller).' does not implement DispatcherInterface'
                );
            }
        }

        return $this->_component;
    }

    /**
     * Method to set a component object attached to the dispatcher
     *
     * @param	mixed	$component An object that implements DispatcherInterface, ObjectIdentifier object
     * 					            or valid identifier string
     * @param  array  $config  An optional associative array of configuration options
     * @return Dispatcher
     */
    public function setComponent($component, $config = array())
    {
        if(!($component instanceof Library\DispatcherInterface))
        {
            if(is_string($component) && strpos($component, '.') === false )
            {
                $identifier			   = $this->getIdentifier()->toArray();!
                $identifier['package'] = $component;

                $identifier = $this->getIdentifier($identifier);
            }
            else $identifier = $this->getIdentifier($component);

            //Set the configuration
            $identifier->getConfig()->append($config);

            $component = $identifier;
        }

        $this->_component = $component;

        return $this;
    }

    /**
     * Resolve the request
     *
     * @param Library\DispatcherContextInterface $context A dispatcher context object
     */
    protected function _resolveRequest(Library\DispatcherContextInterface $context)
    {
        parent::_resolveRequest($context);

        $url = clone $context->request->getUrl();

        //Parse the route
        $this->getRouter()->parse($url);

        //Set the request
        $context->request->query->add($url->query);

        //Resolve the controller
        if($context->request->query->has('component')) {
            $this->setComponent($context->request->query->get('component', 'cmd'));
        }
    }

    /**
     * Forward to the component
     *
     * @param Library\DispatcherContextInterface $context A dispatcher context object
     */
    protected function _actionDispatch(Library\DispatcherContextInterface $context)
    {
        $component = $this->getComponent();

        //Execute the component and pass along the context
        $component->dispatch($context);
    }

    /**
     * Forward to the component
     *
     * @throws \InvalidArgumentException If the action parameter is not an instance of Exception or ExceptionError
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionFail(Library\DispatcherContextInterface $context)
    {
        //Forward to the component
        $component = $this->getComponent();

        //Execute the component and pass along the context
        $component->fail($context);
    }

    /**
     * Forward to the component
     *
     * Redirect to a URL externally. Method performs a 301 (permanent) redirect. Method should be used to immediately
     * redirect the dispatcher to another URL after a GET request.
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionRedirect(Library\DispatcherContextInterface $context)
    {
        //Forward to the component
        $component = $this->getComponent();

        //Execute the component and pass along the context
        $component->redirect($context);
    }
}
