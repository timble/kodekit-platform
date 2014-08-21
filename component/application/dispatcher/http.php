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
class DispatcherHttp extends Library\DispatcherAbstract implements Library\ObjectInstantiable
{
    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config	An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the base url in the request
        $this->getRequest()->setBaseUrl($config->base_url);

        //Render an exception before sending the response
        $this->addCommandCallback('before.fail', '_renderError');

        //Register the default exception handler
        $this->addEventListener('onException', array($this, 'fail'), Library\Event::PRIORITY_LOW);
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
            'response'          => 'com:application.dispatcher.response',
            'controller'        => 'page',
            'base_url'          => '/',
            'event_subscribers' => array('unauthorized'),
            'title'             => 'Application',
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	Library\ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	Library\ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  DispatcherHttp
     */
    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists
        if (!$manager->isRegistered('application'))
        {
            $class     = $manager->getClass($config->object_identifier);
            $instance  = new $class($config);
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
     * Render an error
     *
     * @throws \InvalidArgumentException If the action parameter is not an instance of Library\Exception
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _renderError(Library\DispatcherContextInterface $context)
    {
        $request   = $context->request;
        $response  = $context->response;

        if(in_array($request->getFormat(), array('json', 'html')))
        {
            //Check an exception was passed
            if(!isset($context->param) && !$context->param instanceof Library\Exception)
            {
                throw new \InvalidArgumentException(
                    "Action parameter 'exception' [Library\Exception] is required"
                );
            }

            //Get the exception object
            if($context->param instanceof Library\EventException) {
                $exception = $context->param->getException();
            } else {
                $exception = $context->param;
            }

            $config = array(
                'request'  => $this->getRequest(),
                'response' => $this->getResponse()
            );

            $this->getObject('com:application.controller.error',  $config)
                ->layout('default')
                ->render($context->param->getException());

            //User the 'error' application template
            $context->request->query->set('tmpl', 'error');
        }
    }
}
