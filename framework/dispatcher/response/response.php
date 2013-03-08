<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Dispatcher Response Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Response
 */
class DispatcherResponse extends ControllerResponse implements DispatcherResponseInterface
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
     * @param 	object 	An optional Config object with configuration options.
     */
    public function __construct(Config $config)
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
     * @param 	object 	An optional Config object with configuration options.
     * @return 	void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'transport' => 'default',
            'request'   => 'lib://nooku/dispatcher.request',
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	Config                  $config	  A Config object with configuration options
     * @param 	ServiceManagerInterface	$manager  A ServiceInterface object
     * @return DispatcherRequest
     */
    public static function getInstance(Config $config, ServiceManagerInterface $manager)
    {
        if (!$manager->has('response'))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->setAlias('response', $config->service_identifier);
        }

        return $manager->get('response');
    }

    /**
     * Get the transport strategy
     *
     * @throws	\UnexpectedValueException	If the transport object doesn't implement the
     *                                      DispatcherResponseTransportInterface
     * @return	DispatcherResponseTransportInterface
     */
    public function getTransport()
    {
        if(!$this->_transport instanceof DispatcherResponseTransportInterface)
        {
            if(!($this->_transport instanceof ServiceIdentifier)) {
                $this->setTransport($this->_transport);
            }

            $this->_transport = $this->getService($this->_transport, array('response' => $this));

            if(!$this->_transport instanceof DispatcherResponseTransportInterface)
            {
                throw new \UnexpectedValueException(
                    'Transport: '.get_class($this->_transport).' does not implement DispatcherResponseTransportInterface'
                );
            }
        }

        return $this->_transport;
    }

    /**
     * Method to set a transport strategy
     *
     * @param	mixed	An object that implements ServiceInterface, ServiceIdentifier object
     * 					or valid identifier string
     * @return	DispatcherResponse
     */
    public function setTransport($transport)
    {
        if(!($transport instanceof DispatcherResponseTransportInterface))
        {
            if(is_string($transport) && strpos($transport, '.') === false ) {
                $identifier = 'lib://nooku/dispatcher.response.transport.'.$transport;
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
     * @param DispatcherRequestInterface $request A request object
     * @return DispatcherResponse
     */
    public function setRequest(DispatcherRequestInterface $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Get the request object
     *
     * @throws	\UnexpectedValueException	If the request doesn't implement the ControllerRequestInterface
     * @return ControllerRequestInterface
     */
    public function getRequest()
    {
        if(!$this->_request instanceof DispatcherRequestInterface)
        {
            $this->_request = $this->getService($this->_request);

            if(!$this->_request instanceof DispatcherRequestInterface)
            {
                throw new \UnexpectedValueException(
                    'Request: '.get_class($this->_request).' does not implement DispatcherRequestInterface'
                );
            }
        }

        return $this->_request;
    }

    /**
     * Send the response
     *
     * @return DispatcherResponseTransportInterface
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