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
 * Http Exception Unauthorized Class
 *
 * The request requires user authentication
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Exception
 * @subpackage  Exception
 */
class HttpExceptionUnauthorized extends HttpExceptionAbstract
{
    protected $code = HttpResponse::UNAUTHORIZED;
}