<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Esi Dispatcher Response Transport
 *
 * Transport requires a X-Varnish-Esi-Level header to be present. If it exists and the value > 0 the transport
 * will directly send the response to the client.
 *
 * Example vcl configuration
 *
 * sub vcl_recv
 * {
 *     if (req.esi_level > 0) {
 *         set req.http.X-Varnish-Esi-Level = req.esi_level;
 *     } else {
 *        unset req.http.X-Varnish-Esi-Level;
 *     }
 *  }
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Varnish
 */
class DispatcherResponseTransportEsi extends Library\DispatcherResponseTransportHttp
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    public function send(Library\DispatcherResponseInterface $response)
    {
        $headers = $response->getRequest()->getHeaders();

        //Send the response if we are making a ESI sub request
        if($headers->has('X-Varnish-Esi-Level') && $headers->get('X-Varnish-Esi-Level') > 0) {
            return parent::send($response);
        }
    }
}