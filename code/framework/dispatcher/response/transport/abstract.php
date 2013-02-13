<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Reponse
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Dispatcher Response Transport Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Transport
 */
abstract class KDispatcherResponseTransportAbstract extends KObject implements KDispatcherResponseTransportInterface
{
    /**
     * Response object
     *
     * @var	object
     */
    protected $_response;

    /**
     * Constructor.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if (is_null($config->response))
        {
            throw new \InvalidArgumentException(
                'response [KDispatcherResponseInterface] config option is required'
            );
        }

        if(!$config->response instanceof KDispatcherResponseInterface)
        {
            throw new \UnexpectedValueException(
                'Response: '.get_class($config->response).' does not implement KDispatcherResponseInterface'
            );
        }

        //Set the response
        $this->_response = $config->response;
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
            'response' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the response object
     *
     * @return  object	The response object
     */
    public function getResponse()
    {
        return $this->_response;
    }
}