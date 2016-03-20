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
 * Redirect Dispatcher Response Transport
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 */
class DispatcherResponseTransportRedirect extends DispatcherResponseTransportHttp
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
     * Send HTTP response
     *
     * If this is a redirect response, send the response and stop the transport handler chain.
     *
     * @param DispatcherResponseInterface $response
     * @return boolean
     */
    public function send(DispatcherResponseInterface $response)
    {
        if($response->isRedirect())
        {
            $session  = $response->getUser()->getSession();

            //Set the messages into the session
            $messages = $response->getMessages();

            if(count($messages))
            {
                //Auto start the session if it's not active.
                if(!$session->isActive()) {
                    $session->start();
                }

                $session->getContainer('message')->add($messages);
            }

            //Set the redirect into the response
            $format = $response->getRequest()->getFormat();
            if($format == 'json')
            {
                array_unshift($messages, sprintf('Redirecting to %1$s', $response->getHeaders()->get('Location')));

                $response->setContent(json_encode(array(
                    'messages' => $messages
                )), 'application/json');
            }

            if($format == 'html')
            {
                $response->setContent(sprintf(
                    '<!DOCTYPE html>
                        <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <meta http-equiv="refresh" content="1;url=%1$s" />
                                <title>Redirecting to %1$s</title>
                            </head>
                            <body>
                                Redirecting to <a href="%1$s">%1$s</a>.
                            </body>
                        </html>'
                    , htmlspecialchars($response->getHeaders()->get('Location'), ENT_QUOTES, 'UTF-8')
                ), 'text/html');
            }

            return parent::send($response);
        }
    }
}