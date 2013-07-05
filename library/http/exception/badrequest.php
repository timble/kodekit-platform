<?php
/**
 * @package     Koowa_Http
 * @subpackage  Exception
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Http Exception Bad Request Class
 *
 * The request itself or the data supplied along with the request is invalid and could not be processed by the server.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Exception
 */
class HttpExceptionBadRequest extends HttpExceptionAbstract
{
    protected $code = HttpResponse::BAD_REQUEST;
}