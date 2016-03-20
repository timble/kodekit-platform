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
 * Conflict Http Exception
 *
 * The write request (PUT, POST, DELETE) has been rejected due conflicting changes made by another client, either to the
 * target resource itself or to a related resource. The server cannot currently complete the request without risking data
 * loss. The client should retry the request after accounting for any changes introduced by other clients.
 *
 * This response may include a Retry-After header indicating the time at which the conflicting edits are expected to
 * complete. Clients should wait until at least this time before retrying the request.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Http
 */
class HttpExceptionConflict extends HttpExceptionAbstract
{
    protected $code = HttpResponse::CONFLICT;
}