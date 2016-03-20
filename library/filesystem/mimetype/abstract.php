<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Abstract FileSystem Mimetype Resolver
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Kodekit\Library\Filesystem\Mimetype
 */
abstract class FilesystemMimetypeAbstract extends Object implements FilesystemMimetypeInterface
{
    /**
     * Check if the resolver is supported
     *
     * @return  boolean  True on success, false otherwise
     */
    public static function isSupported()
    {
        return true;
    }
}