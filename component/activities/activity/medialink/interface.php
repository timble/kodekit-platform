<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-activities for the canonical source repository
 */

namespace Kodekit\Component\Activities;

use Kodekit\Library;

/**
 * Activity Stream Media Link Interface.
 *
 * @see     http://activitystrea.ms/specs/json/1.0/#media-link
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
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