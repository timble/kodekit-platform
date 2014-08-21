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
 * X-Sendfile Dispatcher Response Transport
 *
 * X-SendFile allows for internal redirection to a location determined by a header returned from a backend. This allows
 * to handle authentication, logging or whatever else you please in your backend and then have the server serve the
 * contents from redirected location to the client, thus freeing up the backend to handle other requests.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
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
     * Send the specific X-Sendfile HTTP headers for internal processing by the server. For Nginx and Lighttpd 1.4
     * remove the X-Sendfile header and use the specific header instead.
     *
     * If the X-Sendfile header is 1 or TRUE, the response path will be used instead of the path supplied in the
     * header. If X-Sendfile header is  0 or FALSE the header is ignored and removed.
     *
     * - Apache    : X-Sendfile
     * - Nginx     : X-Accel-Redirect
     * - Lightttpd : X-LIGHTTPD-send-file (v1.4) or X-Sendfile (v1.5)
     *
     * @param DispatcherResponseInterface $response
     * @return boolean
     */
    public function send(DispatcherResponseInterface $response)
    {
        if($response->headers->has('X-Sendfile'))
        {
            $path = $response->headers->get('X-Sendfile');

            if($path === true || $path === 1) {
                $path = $response->getStream()->getPath();
            }

            if(is_file($path))
            {
                $server = strtolower($_SERVER['SERVER_SOFTWARE']);

                //Nginx uses X-Accel-Redirect header
                if(strpos($server, 'nginx') !== FALSE)
                {
                    $path = preg_replace('/'.preg_quote(\Nooku::getInstance()->getRootPath(), '/').'/', '', $path, 1);
                    $response->headers->set('X-Accel-Redirect', $path);
                    $response->headers->remove('X-Sendfile');
                }

                //Lighttpd 1.4 uses X-LIGHTTPD-send-file header
                if(strpos($server, 'lightttpd/1.4') !== FALSE)
                {
                    $response->headers->set('X-LIGHTTPD-send-file', $path);
                    $response->headers->remove('X-Sendfile');
                }

                return parent::send($response);
            }
            else $response->headers->remove('X-Sendfile');
        }
    }
}