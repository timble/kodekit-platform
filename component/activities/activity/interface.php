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
 * Activity Interface.
 *
 * In its simplest form, an activity consists of an actor, a verb, an object, and optionally a target. It tells the
 * story of a person performing an action on or with an object -- "Geraldine posted a photo to her album" or "John
 * shared a video". In most cases these components will be explicit, but they may also be implied.
 *
 * @link    http://activitystrea.ms/specs/json/1.0/#activity
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
 */
interface ActivityInterface
{
    /**
     * Get the activity format.
     *
     * An activity format consist on a template for rendering activity messages.
     *
     * @return string The activity format.
     */
    public function getActivityFormat();

    /**
     * Get the activity icon.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See icon property.
     *
     * @return ActivityMedialinkInterface|null The activity icon, null if the activity does not have an
     *                                                      icon.
     */
    public function getActivityIcon();

    /**
     * Get the activity id.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See id property.
     *
     * @return string The activity ID.
     */
    public function getActivityId();

    /**
     * Get the activity published date.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See published property.
     *
     * @return Library\DateInterface The published date.
     */
    public function getActivityPublished();

    /**
     * Get the activity actor.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See actor property.
     *
     * @return ActivityObjectInterface The activity actor object.
     */
    public function getActivityActor();

    /**
     * Get the activity object.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See object property.
     *
     * @return ActivityObjectInterface|null The activity object, null if the activity does not have an
     *                                                   object.
     */
    public function getActivityObject();

    /**
     * Get the activity target.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See target property.
     *
     * @return ActivityObjectInterface|null The activity target object, null if the activity does no have
     *                                                   a target.
     */
    public function getActivityTarget();

    /**
     * Get the activity generator.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See generator property.
     *
     * @return ActivityObjectInterface|null The activity generator object, null if the activity does not
     *                                                   have a generator.
     */
    public function getActivityGenerator();

    /**
     * Get the activity provider.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See provider property.
     *
     * @return ActivityObjectInterface|null The activity provider object, null if the activity does not
     *                                                   have a provider.
     */
    public function getActivityProvider();

    /**
     * Get the activity verb.
     *
     * @link http://activitystrea.ms/specs/json/1.0/#activity See verb property.
     *
     * @return string The activity verb.
     */
    public function getActivityVerb();
}