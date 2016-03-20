<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * X-Sendfile Dispatcher Response Transport
 *
 * X-SendFile allows for internal redirection to a location determined by a header returned from a backend. This allows
 * to handle authentication, logging or whatever else you please in your backend and then have the server serve the
 * contents from redirected location to the client, thus freeing up the backend to handle other requests.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 * @see Apache   : https://tn123.org/mod_xsendfile/
 * @see Nginx    : http://wiki.nginx.org/XSendfile
 * @see Lighttpd : http://redmine.lighttpd.net/projects/1/wiki/X-LIGHTTPD-send-file
 */
class DispatcherResponseTransportSendfile extends DispatcherResponseTransportHttp
{
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
            'priority' => self::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    /**
     * Discard all output and send the file specified by the header instead using server internals.
     *
     * @param DispatcherResponseInterface $response
     * @return DispatcherResponseTransportRedirect
     */
    public function sendContent(DispatcherResponseInterface $response)
    {
        return;
    }

    /**
     * Send HTTP response
     *
     * Send the specific X-Sendfile HTTP headers for internal processing by the server.
     *
     * - Apache : X-Sendfile
     * - Nginx  : X-Accel-Redirect
     *
     * @param DispatcherResponseInterface $response
     * @return boolean
     */
    public function send(DispatcherResponseInterface $response)
    {
        if($response->isDownloadable())
        {
            $server = strtolower($_SERVER['SERVER_SOFTWARE']);

            //Apache
            if(strpos($server, 'apache') !== FALSE)
            {
                if(in_array('mod_xsendfile', apache_get_modules()))
                {
                    $path = $response->getStream()->getPath();

                    $response->headers->set('X-Sendfile', $path);
                    return parent::send($response);
                }
            }

            //Nginx
            if(strpos($server, 'nginx') !== FALSE)
            {
                $path = $response->getStream()->getPath();
                $path = preg_replace('/'.preg_quote(\Kodekit::getInstance()->getRootPath(), '/').'/', '', $path, 1);

                $response->headers->set('X-Accel-Redirect' , $path);
                return parent::send($response);
            }
        }
    }
}