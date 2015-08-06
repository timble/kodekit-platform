<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2015 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Dispatcher request transport for decoding data
 *
 * Decodes the request payload for various content types and pushes the results into the data object
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Dispatcher\Request\Transport
 */
class DispatcherRequestTransportData extends DispatcherRequestTransportAbstract
{
    /**
     * Receive request
     *
     * @param DispatcherRequestInterface $request
     */
    public function receive(DispatcherRequestInterface $request)
    {
        //Set request data
        if($request->getContentType() == 'application/x-www-form-urlencoded')
        {
            if (in_array($request->getMethod(), array('PUT', 'DELETE', 'PATCH')))
            {
                parse_str($request->getContent(), $data);
                $request->getData()->add($data);
            }
        }
        elseif(in_array($request->getContentType(), array('application/json', 'application/x-json', 'application/vnd.api+json')))
        {
            if(in_array($request->getMethod(), array('POST', 'PUT', 'DELETE', 'PATCH')))
            {
                $data = array();

                if ($content = $request->getContent()) {
                    $data = json_decode($content, true);
                }

                $request->getData()->add($data);

                // Transform the JSON API request payloads
                if($request->getContentType() == 'application/vnd.api+json')
                {
                    if (is_array($request->data->data))
                    {
                        $data = $request->data->data;

                        if (isset($data['attributes']) && is_array($data['attributes'])) {
                            $request->data->add($data['attributes']);
                        }
                    }
                }
            }
        }
    }
}