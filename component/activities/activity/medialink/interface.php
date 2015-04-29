<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Stream Media Link Interface.
 *
 * @see     http://activitystrea.ms/specs/json/1.0/#media-link
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
interface ActivityMedialinkInterface
{
    /**
     * Get Duration.
     *
     * @return int|null The duration, null if the media link does not have a duration property.
     */
    public function getDuration();

    /**
     * Set Duration.
     *
     * @param int $duration The duration.
     * @return ActivityMedialinkInterface
     */
    public function setDuration($duration);

    /**
     * Get Height.
     *
     * @return int|null The height, null if the media link does not have a height property.
     */
    public function getHeight();

    /**
     * Set Height.
     *
     * @param int $height The height.
     * @return ActivityMedialinkInterface
     */
    public function setHeight($height);

    /**
     * Get Url.
     *
     * @return Library\HttpUrl The url.
     */
    public function getUrl();

    /**
     * Set Url.
     *
     * @param Library\HttpUrl $url The url.
     * @return ActivityMedialinkInterface
     */
    public function setUrl(Library\HttpUrl $url);

    /**
     * Get Width.
     *
     * @return int|null The width, null if the media link does not have a width property.
     */
    public function getWidth();

    /**
     * Set Width.
     *
     * @param int $width The width.
     * @return ActivityMedialinkInterface
     */
    public function setWidth($width);
}