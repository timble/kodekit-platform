<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Dispatcher Response
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherResponseAbstract extends ControllerResponse implements DispatcherResponseInterface
{
    /**
     * Transport object or identifier
     *
     * @var	string|object
     */
    protected $_transport;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the transport
        $this->_transport = $config->transport;

        //Set the messages
        $this->_messages = $this->getUser()->getSession()->getContainer('message')->all();
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config    An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'transport' => 'default',
        ));

        parent::_initialize($config);
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
            if(!($this->_transport instanceof ObjectIdentifier)) {
                $this->setTransport($this->_transport);
            }

            $this->_transport = $this->getObject($this->_transport, array('response' => $this));

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
     * @param	mixed	$transport An object that implements ObjectInterface, ObjectIdentifier object
     * 					           or valid identifier string
     * @return	DispatcherResponse
     */
    public function setTransport($transport)
    {
        if(!($transport instanceof DispatcherResponseTransportInterface))
        {
            if(is_string($transport) && strpos($transport, '.') === false ) {
                $identifier = 'lib:dispatcher.response.transport.'.$transport;
            } else {
                $identifier = $this->getIdentifier($transport);
            }

            $transport = $identifier;
        }

        $this->_transport = $transport;

        return $this;
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

    /**
     * Deep clone of this instance
     *
     * @return void
     */
    public function __clone()
    {
        parent::__clone();

        $this->_transport  = clone $this->_transport;
    }
}