<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Application Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class Dispatcher extends Library\DispatcherAbstract implements Library\ObjectInstantiable
{
    /**
     * The site identifier.
     *
     * @var string
     */
    private $__site;

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
            'controller' => '',
            'request'    => array(
                'base_url'   => '/'
            ),
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
    public static function getInstance(Library\ObjectConfigInterface $config, Library\ObjectManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists
        if (!$manager->isRegistered('application'))
        {
            $instance  = new static($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the object alias to allow easy access to the singleton
            $manager->registerAlias($config->object_identifier, 'application');
        }

        return $manager->getObject('application');
    }

    /**
     * Get the application router.
     *
     * @param  array $options 	An optional associative array of configuration options.
     * @return	Dispatcher
     */
    public function getRouter(array $options = array())
    {
        return $this->getObject('com:application.dispatcher.router', $options);
    }

    /**
     * Gets the name of site
     *
     * This function tries to get the site name based on the information present in the request.
     * If no site can be found it will return 'default'.
     *
     * @return string  The site name
     */
    public function getSite()
    {
        if (!$this->__site)
        {
            // Check URL host
            $uri = clone($this->getRequest()->getUrl());

            $host = $uri->getHost();
            if (!$this->getObject('application.sites')->find($host))
            {
                // Check folder
                $base = $this->getRequest()->getBaseUrl()->getPath();
                $path = trim(str_replace($base, '', $uri->getPath()), '/');
                if (!empty($path)) {
                    $site = array_shift(explode('/', $path));
                } else {
                    $site = 'default';
                }

                //Check if the site can be found, otherwise use 'default'
                if (!$this->getObject('application.sites')->find($site)) {
                    $site = 'default';
                }

            } else $site = $host;

            $this->__site = $site;
        }

        return $this->__site;
    }

    /**
     * Re-create the session if site has changed
     *
     * @return Library\UserInterface
     */
    public function getUser()
    {
        if(!$this->_user instanceof Library\UserInterface)
        {
            $user    =  parent::getUser();
            $session =  $user->getSession();

            //Re-create the session if we changed sites
            if($user->isAuthentic() && ($session->site != $this->getSite()))
            {
                //@TODO : Fix this
                //if(!$this->getObject('com:users.controller.session')->add()) {
                //    $session->destroy();
                //}
            }
        }

        return parent::getUser();
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

        //Resolve the component
        if($context->request->query->has('component'))
        {
            $identifier  = $this->getIdentifier()->toArray();
            $identifier['package'] = $context->request->query->get('component', 'cmd');

            $this->setController($identifier);
        }
    }

    /**
     * Forward the request
     *
     * @param Library\DispatcherContextInterface $context A dispatcher context object
     */
    protected function _actionDispatch(Library\DispatcherContextInterface $context)
    {
        //Execute the component and pass along the context
        $this->getController()->dispatch($context);
    }

    /**
     * Forward the request
     *
     * @throws \InvalidArgumentException If the action parameter is not an instance of Exception or ExceptionError
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionFail(Library\DispatcherContextInterface $context)
    {
        //Execute the component and pass along the contex
        $this->getController()->fail($context);
    }

    /**
     * Forward the request
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionRedirect(Library\DispatcherContextInterface $context)
    {
        //Execute the component and pass along the context
        $this->getController()->redirect($context);
    }
}
