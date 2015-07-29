<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract FileSystem Mimetype Resolver
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Filesystem\Mimetype
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