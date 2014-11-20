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
 * Unsupported Media Type Http Exception
 *
 * The server is refusing to service the request because the entity of the request is in a format not supported by the
 * requested resource for the requested method.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Http
 */
class HttpExceptionUnsupportedMediaType extends HttpExceptionAbstract
{
    protected $code = HttpResponse::UNSUPPORTED_MEDIA_TYPE;
}