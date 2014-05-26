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
 * Unsupported Media Type Http Exception
 *
 * The server is refusing to service the request because the entity of the request is in a format not supported by the
 * requested resource for the requested method.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Http
 */
class HttpExceptionUnsupportedMediaType extends HttpExceptionAbstract
{
    protected $code = HttpResponse::UNSUPPORTED_MEDIA_TYPE;
}