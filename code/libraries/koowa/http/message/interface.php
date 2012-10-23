<?php
/**
 * @version     $Id: response.php 4675 2012-06-03 01:05:49Z johanjanssens $
 * @package     Koowa_Http
 * @subpackage  Message
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Http Message Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Message
 */
interface KHttpMessageInterface
{
    /**
     * Set the headers container
     *
     * @see    getHeaders()
     * @param  KHttpMessageHeaders $headers
     * @return KHttpMessageInterface
     */
    public function setHeaders(KHttpMessageHeaders $headers);

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version The HTTP protocol version
     * @return KHttpMessage
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
     * @return KHttpMessageInterface
     */
    public function setContent($value);

    /**
     * Get message content
     *
     * @return mixed
     */
    public function getContent();
}