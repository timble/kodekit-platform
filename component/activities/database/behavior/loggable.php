<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-activities
 */

namespace Kodekit\Component\Activities;

use Kodekit\Library;

/**
 * Loggable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Activities
 */
class DatabaseBehaviorLoggable extends Library\DatabaseBehaviorAbstract
{
    /**
     * Get a list of activities
     *
     * @return Library\ModelEntityInterface
     */
    public function getActivities()
    {
        $activities = $this->getObject('com:activities.model.activities')
            ->row($this->id)
            ->package($this->getTable()->getIdentifier()->package)
            ->name($this->getTable()->getName())
            ->fetch();

        return $activities;
    }
}