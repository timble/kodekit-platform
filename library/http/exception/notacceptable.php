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
 * Unauthorized Http Exception
 *
 * The resource identified by the request is only capable of generating response entities which have content
 * characteristics not acceptable according to the accept headers sent in the request.
 *
 * Unless it was a HEAD request, the response SHOULD include an entity containing a list of available entity
 * characteristics and location(s) from which the user or user agent can choose the one most appropriate.
 *
 * The entity format is specified by the media type given in the Content-Type header field. Depending upon the
 * format and the capabilities of the user agent, selection of the most appropriate choice MAY be performed
 * automatically. However, this specification does not define any standard for such automatic selection.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Http
 */
class HttpExceptionNotAcceptable extends HttpExceptionAbstract
{
    protected $code = HttpResponse::NOT_ACCEPTABLE;
}