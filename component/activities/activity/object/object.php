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
 * Activity Stream Object
 *
 * @link    http://activitystrea.ms/specs/json/1.0/#object
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ActivityObject extends Library\ObjectArray implements ActivityObjectInterface
{
    public function __construct(Library\ObjectConfig $config)
    {
        $config->append(array(
            'data' => array(
                'deleted'              => false,
                'internal'             => false,
                'attachments'          => array(),
                'downstreamDuplicates' => array(),
                'upstreamDuplicates'   => array(),
                'attributes'           => array()
            )
        ));

        parent::__construct($config);
    }

    /**
     * Get the activity object name.
     *
     * The object name identifies the object using a human-readable and plain-text string. HTML markup MUST NOT be
     * included.
     *
     * @return string|null The activity object name, null if the object does not have a name.
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * Set the activity object name.
     *
     * @see ActivityObject::getObjectName
     *
     * @param string|null $name The activity object name.
     * @return ActivityObject
     */
    public function setObjectName($name)
    {
        if (!is_null($name)) {
            $name = (string) $name;
        }

        $this->objectName = $name;
        return $this;
    }

    /**
     * Get the display name.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See displayName property.
     *
     * @return string|null The display name, null if the object does not have a display name property.
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set the display name.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See displayName property.
     *
     * @param string|null $name The display name.
     * @return ActivityObject
     */
    public function setDisplayName($name)
    {
        if (!is_null($name)) {
            $name = (string) $name;
        }

        $this->displayName = $name;
        return $this;
    }

    /**
     * Get the attachments.
     *
     * @return array An array of {@link ActivityObjectInterface} objects.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See attachments property.
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Set the attachments.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See attachments property.
     *
     * @param array $attachments An array of {@link ActivityObjectInterface} objects.
     * @param bool  $merge       Tells if attachments should be replaced or merged with current existing attachments.
     *
     * @return ActivityObject
     */
    public function setAttachments(array $attachments, $merge = true)
    {
        if ($merge) {
            $this->attachments = array_merge($this->attachments, $attachments);
        } else {
            $this->attachments = $attachments;
        }

        return $this;
    }

    /**
     * Get the author.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See author property.
     *
     * @return ActivityObjectInterface|null The author, null if the object does not have an actor property.
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the author.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See author property.
     *
     * @param ActivityObjectInterface|null $author The author.
     * @return ActivityObject
     */
    public function setAuthor($author)
    {
        if (!is_null($author) && !$author instanceof ActivityObjectInterface) {
            throw new \InvalidArgumentException('Invalid author type.');
        }

        $this->author = $author;
        return $this;
    }

    /**
     * Get the content.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See content property.
     *
     * @return string|null The content, null if the object does not have a content property.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the content.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See content property.
     *
     * @param string $content The content.
     * @return ActivityObject
     */
    public function setContent($content)
    {
        if (!is_null($content)) {
            $content = (string) $content;
        }

        $this->content = $content;
        return $this;
    }

    /**
     * Get the downstream duplicates.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See downstreamDuplicates property.
     *
     * @return array An array of {@link ActivityObjectInterface} objects.
     */
    public function getDownstreamDuplicates()
    {
        return $this->downstreamDuplicates;
    }

    /**
     * Set the downstream duplicates.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See downstreamDuplicates property.
     *
     * @param array $duplicates An array of {@link ActivityObjectInterface} objects.
     * @param bool  $merge      Tells if downstream duplicates should be replaced or merged with current existing
     *                          downstream duplicates.
     * @return ActivityObject
     */
    public function setDownstreamDuplicates(array $duplicates, $merge = true)
    {
        if ($merge) {
            $this->downstreamDuplicates = array_merge($this->downstreamDuplicates, $duplicates);
        } else {
            $this->downstreamDuplicates = $duplicates;
        }

        return $this;
    }

    /**
     * Get the Id.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See id property.
     *
     * @return string|null The id, null if the object does not have an id property.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Id.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See id property.
     *
     * @param string|null $id The Id.
     * @return ActivityObject
     */
    public function setId($id)
    {
        if (!is_null($id)) {
            $id = (string) $id;
        }

        $this->id = $id;
        return $this;
    }

    /**
     * Get the image.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See image property.
     *
     * @return ActivityMedialinkInterface|null The image, null if the object does not have an image
     *                                                      property.
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the image.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See image property.
     *
     * @param ActivityMedialinkInterface|null $image The image.
     * @return ActivityObject
     */
    public function setImage($image)
    {
        if (!is_null($image) && !$image instanceof ActivityMedialinkInterface) {
            throw new \InvalidArgumentException('Invalid image type.');
        }

        $this->image = $image;
        return $this;
    }

    /**
     * Get the object type.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See objectType property.
     *
     * @return string|null The object type, null if the object does not have an object type property.
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * Set the object type.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See objectType property.
     *
     * @param string|null $type The object type.
     * @return ActivityObject
     */
    public function setObjectType($type)
    {
        if (!is_null($type)) {
            $type = (string) $type;
        }

        $this->objectType = $type;
        return $this;
    }

    /**
     * Get the published date.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See published property.
     *
     * @return Library\Date|null The published date, null if the object does not have a published property.
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set the published date.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See published property.
     *
     * @param Library\Date $date The published date.
     * @return ActivityObject
     */
    public function setPublished($date)
    {
        if (!is_null($date) && !$date instanceof Library\Date) {
            throw new \InvalidArgumentException('Invalid date type.');
        }

        $this->published = $date;
        return $this;
    }

    /**
     * Get the summary.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See summary property.
     *
     * @return string|null The summary, null if the object does not have a summary property.
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set the summary.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See summary property.
     *
     * @param mixed $summary The summary.
     * @return ActivityObject
     */
    public function setSummary($summary)
    {
        if (!is_null($summary)) {
            $summary = (string) $summary;
        }

        $this->summary = $summary;
        return $this;
    }

    /**
     * Get the updated date.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See updated property.
     *
     * @return Library\Date|null The updated date, null if the object does not have an updated date property.
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the updated date.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See updated property.
     *
     * @param Library\Date|null $date The updated date.
     * @return ActivityObject
     */
    public function setUpdated($date)
    {
        if (!is_null($date) && !$date instanceof Library\Date) {
            throw new \InvalidArgumentException('Invalid date type.');
        }

        $this->updated = $date;
        return $this;
    }

    /**
     * Get the upstream duplicates.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See upstreamDuplicates property.
     *
     * @return array An array of {@link ActivityObjectInterface} objects.
     */
    public function getUpstreamDuplicates()
    {
        return $this->upstreamDuplicates;
    }

    /**
     * Set the upstream duplicates.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See upstreamDuplicates property.
     *
     * @param array $duplicates An array of {@link ActivityObjectInterface} objects.
     * @param bool  $merge      Tells if upstream duplicates should be replaced or merged with current existing upstream
     *                          duplicates.
     * @return ActivityObject
     */
    public function setUpstreamDuplicates(array $duplicates, $merge = true)
    {
        if ($merge) {
            $this->downstreamDuplicates = array_merge($this->downstreamDuplicates, $duplicates);
        } else {
            $this->upstreamDuplicates = $duplicates;
        }

        return $this;
    }

    /**
     * Get the url.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See url property.
     *
     * @return Library\HttpUrl|null The url, null if the object does not have a url property.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the url.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See url property.
     *
     * @param Library\HttpUrl|null $url The url.
     * @return ActivityObject
     */
    public function setUrl($url)
    {
        if (!is_null($url) && !$url instanceof Library\HttpUrl) {
            throw new \InvalidArgumentException('Invalid url type.');
        }

        $this->url = $url;
        return $this;
    }

    /**
     * Get the attributes.
     *
     * @return array The attributes.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the attributes.
     *
     * @param array $attributes The attributes.
     * @param bool  $merge      Tells if attributes should be replaced or merged with current existing attributes.
     * @return ActivityObject
     */
    public function setAttributes(array $attribs = array(), $merge = true)
    {
        if ($merge) {
            $this->attributes = array_merge($this->attributes, $attribs);
        } else {
            $this->attributes = $attribs;
        }

        return $this;
    }

    /**
     * Set the deleted state.
     *
     * @param bool $state The deleted state.
     * @return ActivityObject
     */
    public function setDeleted($state)
    {
        $this->deleted = (bool) $state;
        return $this;
    }

    /**
     * Tells if the object has been deleted, i.e. no longer reachable or persisted.
     *
     * @return bool True if the object has been deleted, false otherwise.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }
}