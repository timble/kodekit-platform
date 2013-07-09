<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Redirect Dispatcher Response Transport Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Response
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