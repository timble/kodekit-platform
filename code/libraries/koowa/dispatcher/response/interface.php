<?php
/**
 * @version		$Id: abstract.php 4948 2012-09-03 23:05:48Z johanjanssens $
 * @package		Koowa_Controller
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Controller Response Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Response
 */
interface KControllerResponseInterface extends KHttpResponseInterface
{
    /**
     * Send HTTP headers
     *
     * @return KControllerResponseAbstract
     */
    public function sendHeaders();

    /**
     * Sends content for the current web response.
     *
     * @return KControllerResponseAbstract
     */
    public function sendContent();

    /**
     * Send HTTP response
     *
     * Prepares the Response before it is sent to the client. This method tweaks the headers to ensure that
     * it is compliant with RFC 2616 and calculates or modifies the cache-control header to a sensible and
     * conservative value
     *
     * @see http://tools.ietf.org/html/rfc2616
     * @return KControllerResponseAbstract
     */
    public function send();
}