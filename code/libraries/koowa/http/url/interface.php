<?php
/**
 * @version     $Id: url.php 5054 2012-10-23 19:38:16Z johanjanssens $
 * @package     Koowa_Http
 * @subpackage  Url
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Http Url Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Url
 */
interface KHttpUrlInterface
{
    /**
     * Get the full url, of the format scheme://user:pass@host/path?query#fragment';
     *
     * @param integer $parts A bitmask of binary or'ed HTTP_URL constants; FULL is the default
     * @return  string
     */
    public function getUrl($parts = self::FULL);

    /**
     * Set the url
     *
     * @param   string  $url
     * @return  KHttpUrl
     */
    public function setUrl($url);

    /**
     * Sets the query string in the url, for KHttpUrl::getQuery() and KHttpUrl::$query.
     *
     * This will overwrite any previous values.
     *
     * @param   string|array  $query  The query string to use; for example `foo=bar&baz=dib`.
     * @param   boolean       $merge  If TRUE the data in $query will be merged instead of replaced. Default FALSE.
     * @return  KHttpUrl
     */
    public function setQuery($query, $merge = false);

    /**
     * Returns the query portion as a string or array
     *
     * @param     boolean $toArray If TRUE return an array. Default FALSE
     * @param     boolean $escape  If TRUE escapes '&' to '&amp;' for xml compliance. Default FALSE
     * @return  string|array The query string; e.g., `foo=bar&baz=dib`.
     */
    public function getQuery($toArray = false, $escape = false);

    /**
     * Sets the KHttpUrl::$path array and $format from a string.
     *
     * This will overwrite any previous values. Also, resets the format based
     * on the final path value.
     *
     * @param   string|array  $path The path string or array of elements to use; for example,"/foo/bar/baz/dib".
     *                              A leading slash will *not* create an empty first element; if the string has a
     *                              leading slash, it is ignored.
     * @return  KHttpUrl
     */
    public function setPath($path);

    /**
     * Returns the path portion as a string or array
     *
     * @param   boolean $toArray If TRUE return an array. Default FALSE
     * @return  string|array The path string; e.g., `path/to/site`.
     */
    public function getPath($toArray = false);

    /**
     * Return a string representation of the url.
     *
     * @see    getUrl()
     * @return string
     */
    public function toString();
}