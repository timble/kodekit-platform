<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Loggable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Activities
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