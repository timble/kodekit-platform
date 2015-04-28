<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Fragment Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherFragment extends DispatcherAbstract implements ObjectInstantiable, ObjectMultiton
{
    /**
     * Constructor.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Resolve the request
        $this->addCommandCallback('before.include', '_resolveRequest');
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config 	An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'dispatched'        => false,
            'controller'        => '',
            'controller_action' => 'render',
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  DispatcherHttp
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        //Add the object alias to allow easy access to the singleton
        $manager->registerAlias($config->object_identifier, 'dispatcher.fragment');

        //Merge alias configuration into the identifier
        $config->append($manager->getIdentifier('dispatcher.fragment')->getConfig());

        //Instantiate the class
        $class     = $manager->getClass($config->object_identifier );
        $instance  = new $class($config);

        return $instance;
    }

    /**
     * Get the request object
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the DispatcherRequestInterface
     * @return DispatcherRequest
     */
    public function getRequest()
    {
        if(!$this->_request instanceof DispatcherRequestInterface) {
            $this->_request = clone $this->getObject('dispatcher.request');
        }

        return $this->_request;
    }

    /**
     * Get the response object
     *
     * @throws	\UnexpectedValueException	If the response doesn't implement the DispatcherResponseInterface
     * @return DispatcherResponse
     */
    public function getResponse()
    {
        if(!$this->_response instanceof DispatcherResponseInterface) {
            $this->_response = clone $this->getObject('dispatcher.response');
        }

        return $this->_response;
    }

    /**
     * Resolve the request
     *
     * @param DispatcherContextInterface $context A dispatcher context object
     */
    protected function _resolveRequest(DispatcherContextInterface $context)
    {
        if($controller = ObjectConfig::unbox($context->param))
        {
            $url = $this->getObject('lib:http.url', array('url' => $controller));

            //Set the request query
            $context->request->query->clear()->add($url->getQuery(true));

            //Set the controller
            $identifier = $url->toString(HttpUrl::BASE);
            $identifier = $this->getIdentifier($identifier);

            $this->setController($identifier);
        }

        parent::_resolveRequest($context);
    }

    /**
     * Include the request
     *
     * Dispatch to a controller internally or forward to another component and include the result by returning it.
     * Function makes an internal sub-request, based on the information in the request and passing along the context
     * and will return the result.
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return	mixed
     */
    protected function _actionInclude(DispatcherContextInterface $context)
    {
        return $this->_actionDispatch($context);
    }

    /**
     * Only return the result and do not send the response
     *
     * @param DispatcherContextInterface $context	A dispatcher context object
     * @return mixed
     */
    protected function _actionSend(DispatcherContextInterface $context)
    {
        return $context->result;
    }
}
