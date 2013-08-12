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
 * Http Url Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Http
 */
interface HttpUrlInterface
{
    /**
     * Parse the url from a string or array
     *
     * Partial URLs are also accepted. setUrl() tries its best to parse them correctly. Function also accepts an
     * associative array like parse_url returns.
     *
     * @param   string|array  $url Part(s) of an URL in form of a string or associative array like parse_url() returns
     * @throws  \UnexpectedValueException If the url is not an array a string or cannot be casted to one.
     * @return  HttpUrl
     * @see     parse_url()
     */
    public function setUrl($url);

    /**
     * Get the scheme part of the URL
     *
     * @return string|null
     */
    public function getScheme();

    /**
     * Set the URL scheme
     *
     * @param  string $scheme
     * @return  HttpUrlInterface
     */
    public function setScheme($scheme);

    /**
     * Get the URL user
     *
     * @return string|null
     */
    public function getUser();

    /**
     * Set the URL user
     *
     * @param  string $user
     * @return HttpUrlInterface
     */
    public function setUser($user);

    /**
     * Get the URL password
     *
     * @return string|null
     */
    public function getPass();

    /**
     * Set the URL password
     *
     * @param  string $user
     * @return HttpUrlInterface
     */
    public function setPass($password);

    /**
     * Get the URL host
     *
     * @return string|null
     */
    public function getHost();

    /**
     * Set the URL Host
     *
     * @param  string $host
     * @return HttpUrlInterface
     */
    public function setHost($host);

    /**
     * Get the URL port
     *
     * @return integer|null
     */
    public function getPort();

    /**
     * Set the port part of the URL
     *
     * @param  integer $port
     * @return HttpUrlInterface
     */
    public function setPort($port);

    /**
     * Returns the path portion as a string or array
     *
     * @param   boolean $toArray If TRUE return an array. Default FALSE
     * @return  string|array The path string; e.g., `path/to/site`.
     */
    public function getPath($toArray = false);

    /**
     * Sets the HttpUrl::$path array and $format from a string.
     *
     * This will overwrite any previous values. Also, resets the format based on the final path value.
     *
     * @param   string|array  $path The path string or array of elements to use; for example,"/foo/bar/baz/dib".
     *                              A leading slash will *not* create an empty first element; if the string has a
     *                              leading slash, it is ignored.
     * @return  HttpUrlInterface
     */
    public function setPath($path);

    /**
     * Returns the query portion as a string or array
     *
     * @param     boolean $toArray If TRUE return an array. Default FALSE
     * @param     boolean $escape  If TRUE escapes '&' to '&amp;' for xml compliance. Default FALSE
     * @return  string|array The query string; e.g., `foo=bar&baz=dib`.
     */
    public function getQuery($toArray = false, $escape = false);

    /**
     * Sets the query string in the url
     *
     * @param   string|array  $query  The query string to use; for example `foo=bar&baz=dib`.
     * @param   boolean       $merge  If TRUE the data in $query will be merged instead of replaced. Default FALSE.
     * @return  HttpUrlInterface
     */
    public function setQuery($query, $merge = false);

    /**
     * Get the URL format
     *
     * @return string|null
     */
    public function getFormat();

    /**
     * Set the URL format
     *
     * @param  string $format
     * @return HttpUrlInterface
     */
    public function setFormat($format);

    /**
     * Get the URL fragment
     *
     * @return string|null
     */
    public function getFragment();

    /**
     * Set the URL fragment part
     *
     * @param  string $fragment
     * @return HttpUrlInterface
     */
    public function setFragment($fragment);

    /**
     * Build the url from a string
     *
     * Partial URLs are also accepted. fromString tries its best to parse them correctly.
     *
     * @param   string  $url
     * @throws  \UnexpectedValueException If the url is not a string or cannot be casted to one.
     * @return  HttpUrl
     * @see     parse_url()
     */
    public static function fromString($url);

    /**
     * Build the url from an array
     *
     * @param   string  $array Associative array like parse_url() returns.
     * @return  HttpUrl
     * @see     parse_url()
     */
    public static function fromArray(array $parts);

    /**
     * Convert the url or part of it to a string
     *
     * Using scheme://user:pass@host/path?query#fragment';
     *
     * @param integer $parts A bitmask of binary or'ed HTTP_URL constants; FULL is the default
     * @return string
     */
    public function toString($parts = self::FULL);
}