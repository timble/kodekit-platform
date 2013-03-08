<?php
/**
 * @package     Koowa_Http
 * @subpackage  Message
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Http Message Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Message
 */
interface HttpMessageInterface
{
    /**
     * Set the header parameters
     *
     * @param  array $headers
     * @return HttpMessageInterface
     */
    public function setHeaders($parameters);

    /**
     * Get the headers container
     *
     * @param  array $headers
     * @return HttpMessageHeaders
     */
    public function getHeaders();

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version The HTTP protocol version
     * @return HttpMessage
     */
    public function setVersion($version);

    /**
     * Gets the HTTP protocol version.
     *
     * @return string The HTTP protocol version
     */
    public function getVersion();

    /**
     * Set message content
     *
     * @param  mixed $value
     * @return HttpMessageInterface
     */
    public function setContent($value);

    /**
     * Get message content
     *
     * @return mixed
     */
    public function getContent();

    /**
     * Sets the message content type
     *
     * @param string $type Content type
     * @return HttpMessage
     */
    public function setContentType($type);

    /**
     * Retrieves the message content type
     *
     * @return string Character set
     */
    public function getContentType();

    /**
     * Render the message as a string
     *
     * @return string
     */
    public function toString();
}