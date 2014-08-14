<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Http Dispatcher Response Transport
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Koowa\Library\Dispatcher
 */
class DispatcherResponseTransportHttp extends Library\DispatcherResponseTransportHttp
{
    /**
     * Send HTTP response
     *
     * @param Library\DispatcherResponseInterface $response
     * @return boolean
     */
    public function send(Library\DispatcherResponseInterface $response)
    {
        $request = $response->getRequest();

        //Render the page
        if($request->getFormat() == 'html')
        {
            $layout = $request->query->get('tmpl', 'cmd', 'default');
            $this->getObject('com:application.controller.page', array('response' => $response))
                ->layout($layout)
                ->render();
        }

        return parent::send($response);
    }
}