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
 * Activity Stream Media Link.
 *
 * @link     http://activitystrea.ms/specs/json/1.0/#media-link
 *
 * @author   Arunas Mazeika <https://github.com/amazeika>
 * @package  Nooku\Component\Activities
 */
class ActivityMedialink extends Library\ObjectArray implements ActivityMedialinkInterface
{
    /**
     * Get Duration.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#media-link See duration property.
     *
     * @return int|null The duration, null if the media link does not have a duration property.
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set Duration.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#media-link See duration property.
     *
     * @param int $duration The duration.
     * @return ActivityMedialink
     */
    public function setDuration($duration)
    {
        $this->duration = (int) $duration;
        return $this;
    }

    /**
     * Get Height.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#media-link See height property.
     *
     * @return int|null The height, null if the media link does not have a height property.
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set Height.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#media-link See height property.
     *
     * @param int $height The height.
     * @return ActivityMedialink
     */
    public function setHeight($height)
    {
        $this->height = (int) $height;
        return $this;
    }

    /**
     * Get Url.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#media-link See Url property.
     *
     * @return Library\HttpUrlInterface The url.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set Url.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#media-link See Url property.
     *
     * @param Library\HttpUrlInterface $url The url.
     * @return ActivityMedialink
     */
    public function setUrl(Library\HttpUrl $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get Width.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#media-link See width property.
     *
     * @return int|null The width, null if the media link does not have a width property.
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set Width.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#media-link See width property.
     *
     * @param int $width The width.
     * @return ActivityMedialink
     */
    public function setWidth($width)
    {
        $this->width = (int) $width;
        return $this;
    }
}