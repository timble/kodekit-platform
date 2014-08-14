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
 * Method Not Allowed Http Exception
 *
 * The request URL does not support the specific request method.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Http
 */
class HttpExceptionMethodNotAllowed extends HttpExceptionAbstract
{
    protected $code = HttpResponse::METHOD_NOT_ALLOWED;
}