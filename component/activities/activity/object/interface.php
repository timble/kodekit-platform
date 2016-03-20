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
 * Activity Object Interface.
 *
 * @link    http://activitystrea.ms/specs/json/1.0/#object
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
 */
interface ActivityObjectInterface
{
    /**
     * Get the activity object name.
     *
     * The object name identifies the object using a human-readable and plain-text string. HTML markup MUST NOT be
     * included.
     *
     * @return string|null The activity object name, null if the object does not have a name.
     */
    public function getObjectName();

    /**
     * Set the activity object name.
     *
     * @see ActivityObjectInterface::getObjectName
     *
     * @param string|null $name The activity object name.
     * @return ActivityObjectInterface
     */
    public function setObjectName($name);

    /**
     * Get the display name.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See displayName property.
     *
     * @return string|null The display name, null if the object does not have a display name property.
     */
    public function getDisplayName();

    /**
     * Set the display name.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See displayName property.
     *
     * @param string|null $name The display name.
     * @return ActivityObjectInterface
     */
    public function setDisplayName($name);

    /**
     * Get the attachments.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See attachments property.
     * @return array An array of {@link ActivityObjectInterface} objects.
     */
    public function getAttachments();

    /**
     * Set the attachments.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See attachments property.
     *
     * @param array $attachments An array of {@link ActivityObjectInterface} objects.
     * @param bool  $merge       Tells if attachments should be replaced or merged with current existing attachments.
     * @return ActivityObjectInterface
     */
    public function setAttachments(array $attachments, $merge = true);

    /**
     * Get the author.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See author property.
     *
     * @return ActivityObjectInterface|null The author, null if the object does not have an actor property.
     */
    public function getAuthor();

    /**
     * Set the author.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See author property.
     *
     * @param ActivityObjectInterface|null $author The author.
     * @return ActivityObjectInterface
     */
    public function setAuthor($author);

    /**
     * Get the content.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See content property.
     *
     * @return string|null The content, null if the object does not have a content property.
     */
    public function getContent();

    /**
     * Set the content.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See content property.
     *
     * @param string|null $content The content.
     * @return ActivityObjectInterface
     */
    public function setContent($content);

    /**
     * Get the downstream duplicates.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See downstreamDuplicates property.
     *
     * @return array An array of {@link ActivityObjectInterface} objects.
     */
    public function getDownstreamDuplicates();

    /**
     * Set the downstream duplicates.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See downstreamDuplicates property.
     *
     * @param array $duplicates An array of {@link ActivityObjectInterface} objects.
     * @param bool  $merge      Tells if downstream duplicates should be replaced or merged with current existing
     *                          downstream duplicates.
     *
     * @return ActivityObjectInterface
     */
    public function setDownstreamDuplicates(array $duplicates, $merge = true);

    /**
     * Get the Id.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See id property.
     *
     * @return string|null The id, null if the object does not have an id property.
     */
    public function getId();

    /**
     * Set the Id.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See id property.
     *
     * @param string|null $id The Id.
     * @return ActivityObjectInterface
     */
    public function setId($id);

    /**
     * Get the image.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See image property.
     *
     * @return ActivityMedialinkInterface|null The image, null if the object does not have an image
     *                                                      property.
     */
    public function getImage();

    /**
     * Set the image.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See image property.
     *
     * @param ActivityMedialinkInterface|null $image The image.
     * @return ActivityObjectInterface
     */
    public function setImage($image);

    /**
     * Get the object type.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See objectType property.
     *
     * @return string|null The object type, null if the object does not have an object type property.
     */
    public function getObjectType();

    /**
     * Set the object type.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See objectType property.
     *
     * @param string|null $type The object type.
     * @return ActivityObjectInterface
     */
    public function setObjectType($type);

    /**
     * Get the published date.
     *
     * @return Library\DateInterface|null The published date, null if the object does not have a published property.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See published property.
     */
    public function getPublished();

    /**
     * Set the published date.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See published property.
     *
     * @param Library\DateInterface|null $date The published date.
     * @return ActivityObjectInterface
     */
    public function setPublished($date);

    /**
     * Get the summary.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See summary property.
     *
     * @return string|null The summary, null if the object does not have a summary property.
     */
    public function getSummary();

    /**
     * Set the summary.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See summary property.
     *
     * @param mixed $summary The summary.
     * @return ActivityObjectInterface
     */
    public function setSummary($summary);

    /**
     * Get the updated date.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See updated property.
     *
     * @return Library\DateInterface|null The updated date, null if the object does not have an updated date property.
     */
    public function getUpdated();

    /**
     * Set the updated date.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See updated property.
     *
     * @param Library\DateInterface|null $date The updated date.
     * @return ActivityObjectInterface
     */
    public function setUpdated($date);

    /**
     * Get the upstream duplicates.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See upstreamDuplicates property.
     *
     * @return array An array of {@link ActivityObjectInterface} objects.
     */
    public function getUpstreamDuplicates();

    /**
     * Set the upstream duplicates.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See upstreamDuplicates property.
     *
     * @param array $duplicates An array of {@link ActivityObjectInterface} objects.
     * @param bool $merge Tells if upstream duplicates should be replaced or merged with current existing upstream
     *                    duplicates.
     * @return ActivityObjectInterface
     */
    public function setUpstreamDuplicates(array $duplicates, $merge = true);

    /**
     * Get the url.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See url property.
     *
     * @return Library\HttpUrlInterface|null The url, null if the object does not have a url property.
     */
    public function getUrl();

    /**
     * Set the url.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#object See url property.
     *
     * @param Library\HttpUrlInterface|null $url The url.
     * @return ActivityObjectInterface
     */
    public function setUrl($url);

    /**
     * Get the attributes.
     *
     * @return array The attributes.
     */
    public function getAttributes();

    /**
     * Set the attributes.
     *
     * @param array $attributes The attributes.
     * @param bool  $merge      Tells if attributes should be replaced or merged with current existing attributes.
     * @return ActivityObjectInterface
     */
    public function setAttributes(array $attribs = array(), $merge = true);

    /**
     * Set the deleted state.
     *
     * @param bool $state The deleted state.
     * @return ActivityObjectInterface
     */
    public function setDeleted($state);

    /**
     * Tells if the object has been deleted, i.e. no longer reachable or persisted.
     *
     * @return bool True if the object has been deleted, false otherwise.
     */
    public function isDeleted();
}