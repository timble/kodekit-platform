<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Whitespace FileSystem Stream Filter
 *
 * Filter which removes all spaces from the stream.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\FileSystem\Stream\Filter\Whitespace
 */
class FilesystemStreamFilterWhitespace extends FilesystemStreamFilterAbstract
{
    /**
     * The filter name
     *
     * @var string
     */
    protected static $_name = 'whitespace';

    /**
     * Called when applying the filter
     *
     * @param resource $in  Resource pointing to a bucket brigade which contains one or more bucket objects containing
     *                      data to be filtered
     * @param resource $out Resource pointing to a second bucket brigade into which your modified buckets should be
     *                      placed.
     * @param integer $consumed Consumed, which must always be declared by reference, should be incremented by the length
     *                          of the data which your filter reads in and alters. In most cases this means you will
     *                          increment consumed by $bucket->datalen for each $bucket.
     * @param bool $closing If the stream is in the process of closing (and therefore this is the last pass through the
     *                      filterchain), the closing parameter will be set to TRUE.
     * @return int
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        while($bucket = stream_bucket_make_writeable($in))
        {
            $bucket->data = trim(preg_replace('/>\s+</', '><', $bucket->data));
            $consumed += $bucket->datalen;
            stream_bucket_prepend($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}