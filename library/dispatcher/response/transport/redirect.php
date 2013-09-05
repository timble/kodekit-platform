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
 * Redirect Dispatcher Response Transport
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherResponseTransportRedirect extends DispatcherResponseTransportAbstract
{
    /**
     * Sends content for the current web response.
     *
     * @return DispatcherResponseTransportRedirect
     */
    public function sendContent()
    {
        $response = $this->getResponse();
        $session  = $response->getUser()->getSession();

        //Set the messages into the session
        $messages = $response->getMessages();
        if(count($messages))
        {
            //Auto start the session if it's not active.
            if(!$session->isActive()) {
                $session->start();
            }

            $session->getContainer('message')->values($messages);
        }

        //Set the redirect into the response
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
            , htmlspecialchars($response->headers->get('Location'), ENT_QUOTES, 'UTF-8')
        ));

        return parent::sendContent();
    }
}