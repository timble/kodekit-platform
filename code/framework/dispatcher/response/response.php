<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Dispatcher Response Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Response
 */
class KDispatcherResponse extends KControllerResponse implements KDispatcherResponseInterface
{
    /**
     * Transport object or identifier
     *
     * @var	string|object
     */
    protected $_transport;

    /**
     * Request object or identifier
     *
     * @var	string|object
     */
    protected $_request;

    /**
     * Constructor.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //Set the transport
        $this->_transport = $config->transport;

        //Set the request
        $this->_request = $config->request;
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
            'transport' => 'default',
            'request'   => 'koowa:dispatcher.request',
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KServiceInterface object
     * @return KDispatcherRequest
     */
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        // Check if an instance with this identifier already exists or not
        if (!$container->has('response'))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);

            $container->setAlias('response', $config->service_identifier);
        }

        return $container->get('response');
    }

    /**
     * Get the transport strategy
     *
     * @throws	\UnexpectedValueException	If the transport object doesn't implement the
     *                                      KDispatcherResponseTransportInterface
     * @return	KDispatcherResponseTransportInterface
     */
    public function getTransport()
    {
        if(!$this->_transport instanceof KDispatcherResponseTransportInterface)
        {
            if(!($this->_transport instanceof KServiceIdentifier)) {
                $this->setTransport($this->_transport);
            }

            $this->_transport = $this->getService($this->_transport, array('response' => $this));

            if(!$this->_transport instanceof KDispatcherResponseTransportInterface)
            {
                throw new \UnexpectedValueException(
                    'Transport: '.get_class($this->_transport).' does not implement KDispatcherResponseTransportInterface'
                );
            }
        }

        return $this->_transport;
    }

    /**
     * Method to set a transport strategy
     *
     * @param	mixed	An object that implements KObjectServiceable, KServiceIdentifier object
     * 					or valid identifier string
     * @return	KDispatcherResponse
     */
    public function setTransport($transport)
    {
        if(!($transport instanceof KDispatcherResponseTransportInterface))
        {
            if(is_string($transport) && strpos($transport, '.') === false ) {
                $identifier = 'koowa:dispatcher.response.transport.'.$transport;
            } else {
                $identifier = $this->getIdentifier($transport);
            }

            $transport = $identifier;
        }

        $this->_transport = $transport;

        return $this;
    }

    /**
     * Set the request object
     *
     * @param KDispatcherRequestInterface $request A request object
     * @return KDispatcherResponse
     */
    public function setRequest(KDispatcherRequestInterface $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Get the request object
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the KControllerRequestInterface
     * @return KControllerRequestInterface
     */
    public function getRequest()
    {
        if(!$this->_request instanceof KDispatcherRequestInterface)
        {
            $this->_request = $this->getService($this->_request);

            if(!$this->_request instanceof KDispatcherRequestInterface)
            {
                throw new \UnexpectedValueException(
                    'Request: '.get_class($this->_request).' does not implement KDispatcherRequestInterface'
                );
            }
        }

        return $this->_request;
    }

    /**
     * Send the response
     *
     * @return KDispatcherResponseTransportInterface
     */
    public function send()
    {
        $transport = null;

        //Force to use the json transport if format is json
        if($this->getRequest()->getFormat() == 'json') {
           $transport = 'json';
        }

        //Force to use the redirect transport if we are redirecting
        if($this->isRedirect()) {
            $transport = 'redirect';
        }

        //If transport is being forced set it
        if($transport) {
            $this->setTransport($transport);
        }

        $this->getTransport()->send();
    }
}