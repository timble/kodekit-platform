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
 * Not Implemented Http Exception
 *
 * The server does not support the functionality required to fulfill the request.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Http
 */
class HttpExceptionNotImplemented extends HttpExceptionAbstract
{
    protected $code = HttpResponse::NOT_IMPLEMENTED;
}