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
 * FileSystem Mimetype Resolver Interface
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Kodekit\Library\Filesystem\Mimetype
 */
interface FilesystemMimetypeInterface
{
    /**
     * Find the mime type of the file with the given path.
     *
     * @param string $path The path to the file
     * @return string The mime type or NULL, if none could be guessed
     */
    public function fromPath($path);

    /**
     * Find the mime type of the given stream
     *
     * @param FilesystemStreamInterface $stream
     * @return string The mime type or NULL, if none could be guessed
     */
    public function fromStream(FilesystemStreamInterface $stream);

    /**
     * Check if the finder is supported
     *
     * @return  boolean  True on success, false otherwise
     */
    public static function isSupported();
}