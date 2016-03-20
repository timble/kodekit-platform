<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2015 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Dispatcher request transport for request headers
 *
 * Pushes the request headers into the headers object
 *
 * @see     Kodekit\Library\HttpRequestHeaders
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Kodekit\Library\Dispatcher\Request\Transport
 */
class DispatcherRequestTransportHeaders extends DispatcherRequestTransportAbstract
{
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    /**
     * Receive request
     *
     * @param DispatcherRequestInterface $request
     */
    public function receive(DispatcherRequestInterface $request)
    {
        //Set the headers
        $headers = array();
        foreach ($_SERVER as $key => $value)
        {
            if ($value && strpos($key, 'HTTP_') === 0)
            {
                // Cookies are handled using the $_COOKIE superglobal
                if (strpos($key, 'HTTP_COOKIE') === 0) {
                    continue;
                }

                $headers[substr($key, 5)] = $value;
            }
            elseif ($value && strpos($key, 'CONTENT_') === 0)
            {
                $name = substr($key, 8); // Content-
                $name = 'Content-' . (($name == 'MD5') ? $name : ucfirst(strtolower($name)));

                $headers[$name] = $value;
            }
        }

        $request->getHeaders()->add($headers);
    }
}