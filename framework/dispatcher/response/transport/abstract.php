<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Reponse
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract Dispatcher Response Transport Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Transport
 */
abstract class DispatcherResponseTransportAbstract extends Object implements DispatcherResponseTransportInterface
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
     * @param 	object 	An optional Config object with configuration options.
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        if (is_null($config->response))
        {
            throw new \InvalidArgumentException(
                'response [DispatcherResponseInterface] config option is required'
            );
        }

        if(!$config->response instanceof DispatcherResponseInterface)
        {
            throw new \UnexpectedValueException(
                'Response: '.get_class($config->response).' does not implement DispatcherResponseInterface'
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
     * @param 	object 	An optional Config object with configuration options.
     * @return 	void
     */
    protected function _initialize(Config $config)
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