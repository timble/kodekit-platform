<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Http
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Static HTTP class
 *
 * @todo Add other statuses
 * @see http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Http
 */
class KHttp
{
	/**
	 * The request contains bad syntax or cannot be fulfilled.
	 */
	const STATUS_BAD_REQUEST = 400;

	/**
	 * Similar to 403 Forbidden, but specifically for use when authentication is
	 * possible but has failed or not yet been provided. See Basic access
	 * authentication and Digest access authentication.
	 */
	const STATUS_UNAUTHORIZED = 401;

	/**
	 * The original intention was that this code might be used as part of some
	 * form of digital cash or micropayment scheme, but that has not happened,
	 * and this code has never been used.
	 */
	const STATUS_PAYMENT_REQUIRED = 402;

	/*
	 * The request was a legal request, but the server is refusing to respond to
	 * it. Unlike a 401 Unauthorized response, authenticating will make no
	 * difference.
	 */
	const STATUS_FORBIDDEN = 403;

	/**
	 * The requested resource could not be found but may be available again in
	 * the future. Subsequent requests by the client are permissible.
	 */
	const STATUS_NOT_FOUND = 404;

	/**
	 * A request was made of a resource using a request method not supported by
	 * that resource; for example, using GET on a form which requires data to be
	 * presented via POST, or using PUT on a read-only resource.
	 */
	const STATUS_METHOD_NOT_ALLOWED = 405;

	/**
	 * The requested resource is only capable of generating content not
	 * acceptable according to the Accept headers sent in the request.
	 */
    const STATUS_NOT_ACCEPTABLE = 406;

    /**
     * Proxy Authentication Required
     */
    const STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * The server timed out waiting for the request.
     */
    const STATUS_REQUEST_TIMEOUT = 408;

}