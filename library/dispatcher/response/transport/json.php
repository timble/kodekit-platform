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
 * JSON Dispatcher Response Transport
 *
 * Response represents an HTTP response in JSON format.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherResponseTransportJson extends DispatcherResponseTransportHttp
{
    /**
     * The padding for JSONP
     *
     * @var string
     */
    protected $_padding;

    /**
     * Constructor
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_padding = $config->padding;
    }

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_NORMAL,
            'padding'  => '',
        ));

        parent::_initialize($config);
    }

    /**
     * Sets the JSONP callback.
     *
     * @param string $callback
     * @throws \InvalidArgumentException If the padding is not a valid javascript identifier
     * @return DispatcherResponseTransportJson
     */
    public function setCallback($callback)
    {
        // taken from http://www.geekality.net/2011/08/03/valid-javascript-identifier/
        $pattern = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';
        $parts = explode('.', $callback);

        foreach ($parts as $part)
        {
            if (!preg_match($pattern, $part)) {
                throw new \InvalidArgumentException('The callback name is not valid.');
            }
        }

        $this->_padding = $callback;
    }

    /**
     * Send HTTP response
     *
     * If the format is json, add the padding by inspect the request query for a 'callback' parameter or by using
     * the default padding if set.
     *
     * Don't stop the transport handler chain to allow other transports handlers to continue processing the
     * response.
     *
     * @link http://tools.ietf.org/html/rfc2616
     *
     * @param DispatcherResponseInterface $response
     * @return boolean
     */
    public function send(DispatcherResponseInterface $response)
    {
        $request = $response->getRequest();

        //Force to use the json transport if format is json
        if($request->getFormat() == 'json')
        {
            //If not padding is set inspect the request query.
            if(empty($this->_padding))
            {
                if($request->query->has('callback')) {
                    $this->setCallback($request->query->get('callback', 'cmd'));
                }
            }

            if (!empty($this->_padding)) {
                $response->setContent(sprintf('%s(%s);', $this->_padding, $response->getContent()));
            }
        }
    }
}