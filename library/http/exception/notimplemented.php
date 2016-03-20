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
 * Not Implemented Http Exception
 *
 * The server does not support the functionality required to fulfill the request.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Http
 */
class HttpExceptionNotImplemented extends HttpExceptionAbstract
{
    protected $code = HttpResponse::NOT_IMPLEMENTED;
}