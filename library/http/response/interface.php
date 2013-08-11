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
 * Http Response Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Http
 */
interface HttpResponseInterface extends HttpMessageInterface
{
    /**
     * Set HTTP status code and (optionally) message
     *
     * @param  integer $code
     * @param  string  $message
     * @throws \InvalidArgumentException
     * @return HttpResponse
     */
    public function setStatus($code, $message = null);

    /**
     * Retrieve HTTP status code
     *
     * @return int
     */
    public function getStatusCode();

    /**
     * Get the http header status message based on a status code
     *
     * @return string The http status message
     */
    public function getStatusMessage();

    /**
     * Sets the response content type
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     *
     * @param string $type Content type
     * @return HttpResponse
     */
    public function setContentType($type);

    /**
     * Retrieves the response content type
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     *
     * @return string Character set
     */
    public function getContentType();

    /**
     * Returns the Date header as a DateTime instance.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.18
     *
     * @return \DateTime A \DateTime instance
     * @throws \RuntimeException When the header is not parseable
     */
    public function getDate();

    /**
     * Sets the Date header.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.18
     *
     * @param \DateTime $date A \DateTime instance
     * @return HttpResponse
     */
    public function setDate(\DateTime $date);

    /**
     * Returns the Last-Modified HTTP header as a DateTime instance.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.29
     *
     * @return \DateTime A DateTime instance
     */
    public function getLastModified();

    /**
     * Sets the Last-Modified HTTP header with a DateTime instance.
     *
     * If passed a null value, it removes the header.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.29
     *
     * @param \DateTime $date A \DateTime instance
     * @return HttpResponseInterface
     */
    public function setLastModified(\DateTime $date = null);

    /**
     * Returns the value of the Expires header as a DateTime instance.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21
     *
     * @return \DateTime A DateTime instance
     */
    public function getExpires();

    /**
     * Sets the Expires HTTP header with a DateTime instance.
     *
     * If passed a null value, it removes the header.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21
     *
     * @param \DateTime $date A \DateTime instance
     * @return HttpResponse
     */
    public function setExpires(\DateTime $date = null);

    /**
     * Returns the literal value of the ETag HTTP header.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.19
     *
     * @return string The ETag HTTP header
     */
    public function getEtag();

    /**
     * Sets the ETag value.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.19
     *
     * @param string  $etag The ETag unique identifier
     * @param Boolean $weak Whether you want a weak ETag or not
     * @return HttpResponse
     */
    public function setEtag($etag = null, $weak = false);

    /**
     * Returns the age of the response.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.6
     * @return integer The age of the response in seconds
     */
    public function getAge();

    /**
     * Sets the number of seconds after the time specified in the response's Date header when the the response
     * should no longer be considered fresh.
     *
     * Uses the expires header to calculate the maximum age. It returns null when no max age can be established.
     *
     * @return integer|null Number of seconds
     */
    public function getMaxAge();

    /**
     * Is the response invalid
     *
     * @return Boolean
     */
    public function isInvalid();

    /**
     * Check if an http status code is an error
     *
     * @return boolean TRUE if the status code is an error code
     */
    public function isError();

    /**
     * Do we have a redirect
     *
     * @return bool
     */
    public function isRedirect();

    /**
     * Was the response successful
     *
     * @return bool
     */
    public function isSuccess();

    /**
     * Returns true if the response includes headers that can be used to validate the response with the origin
     * server using a conditional GET request.
     *
     * @return Boolean true if the response is validateable, false otherwise
     */
    public function isValidateable();

    /**
     * Returns true if the response is worth caching under any circumstance.
     *
     * Responses with that are stale (Expired) or without cache validation (Last-Modified, ETag) headers are
     * considered uncacheable.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9.1
     * @return Boolean true if the response is worth caching, false otherwise
     */
    public function isCacheable();

    /**
     * Returns true if the response is "stale".
     *
     * When the responses is stale, the response may not be served from cache without first re-validating with
     * the origin.
     *
     * @return Boolean true if the response is fresh, false otherwise
     */
    public function isStale();
}