<?php
/**
 * @package		Koowa_Controller
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Controller Response Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Response
 */
interface ControllerResponseInterface extends HttpResponseInterface
{
    /**
     * Sets a redirect
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3
     *
     * @param  string   $location   The redirect location
     * @param  string   $message    The redirect message
     * @param  string   $code       The redirect status code
     * @throws \InvalidArgumentException If the location is empty
     * @throws \UnexpectedValueException If the location is not a string, or cannot be cast to a string
     * @return DispatcherResponse
     */
    public function setRedirect($location, $message = null, $code = self::SEE_OTHER);
}