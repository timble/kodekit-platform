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
 * Activity Logger Interface.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
interface ActivityLoggerInterface
{
    /**
     * Log an activity.
     *
     * @param string                     $action  The action to log.
     * @param Library\ModelEntityInterface      $object  The activity object on which the action is performed.
     * @param Library\ObjectIdentifierInterface $subject The activity subject who is performing the action.
     */
    public function log($action, Library\ModelEntityInterface $object, Library\ObjectIdentifierInterface $subject);

    /**
     * Return the list of actions the logger should listen to for logging.
     *
     * @return array The list of actions.
     */
    public function getActions();

    /**
     * Set the list of actions the logger should listen to for logging.
     *
     * @param array $actions The list of actions.
     * @return ActivityLoggerInterface
     */
    public function setActions($actions);

    /**
     * Get the activity object.
     *
     * The activity object is the entity on which the action is executed.
     *
     * @param Library\CommandInterface $command The command.
     * @return Library\ModelEntityInterface The activity object.
     */
    public function getActivityObject(Library\CommandInterface $command);

    /**
     * Get the activity subject.
     *
     * The activity subject is the identifier of the object that executes the action.
     *
     * @param Library\CommandInterface $command The command.
     * @return Library\ObjectIdentifier The activity subject.
     */
    public function getActivitySubject(Library\CommandInterface $command);

    /**
     * Get the activity status.
     *
     * The activity status is the current status of the activity object.
     *
     * @param Library\ModelEntityInterface $object The activity object.
     * @param string                $action The action being executed.
     * @return string The activity status.
     */
    public function getActivityStatus(Library\ModelEntityInterface $object, $action = null);

    /**
     * Get the activity data.
     *
     * @param Library\ModelEntityInterface      $object  The activity object.
     * @param Library\ObjectIdentifierInterface $subject The activity subject.
     * @return array Activity data.
     */
    public function getActivityData(Library\ModelEntityInterface $object, Library\ObjectIdentifierInterface $subject);
}