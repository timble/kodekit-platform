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
 * Dispatcher request transport for $_SERVER
 *
 * Fixes the common problems in $_SERVER array and sets protocol data in the request object
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Kodekit\Library\Dispatcher\Request\Transport
 */
class DispatcherRequestTransportServer extends DispatcherRequestTransportAbstract
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
        //Set document root for IIS
        if(!isset($_SERVER['DOCUMENT_ROOT']))
        {
            if(isset($_SERVER['SCRIPT_FILENAME'])) {
                $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
            }

            if(isset($_SERVER['PATH_TRANSLATED'])) {
                $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
            }
        }

        //When using PHP-FPM HTTP_AUTHORIZATION is called REDIRECT_HTTP_AUTHORIZATION
        if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        //Set the version
        if (isset($_SERVER['SERVER_PROTOCOL']) && strpos($_SERVER['SERVER_PROTOCOL'], '1.0') !== false) {
            $request->setVersion('1.0');
        }
    }
}