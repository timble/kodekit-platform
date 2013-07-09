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
 * Http Exception Not Found Class
 *
 * The server refused to fulfill the request, for reasons other than invalid user credentials.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Exception
 */
class HttpExceptionForbidden extends HttpExceptionAbstract
{
    protected $code = HttpResponse::FORBIDDEN;
}