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
 * Purgeable Controller Behavior.
 *
 * Adds purge action to the controller.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
 */
class ControllerBehaviorPurgeable extends Library\ControllerBehaviorAbstract
{
    /**
     * Purge action.
     *
     * Deletes activities given a date range.
     *
     * @param Library\ControllerContextInterface $context A command context object.
     * @throws Library\ControllerExceptionActionFailed If the activities cannot be purged.
     */
    protected function _actionPurge(Library\ControllerContextInterface $context)
    {
        $model = $this->getModel();
        $state = $model->getState();
        $query = $this->getObject('lib:database.query.delete');

        $query->table(array($model->getTable()->getName()));

        if ($state->end_date && $state->end_date != '0000-00-00')
        {
            $end_date = $this->getObject('lib:date', array('date' => $state->end_date));
            $end      = $end_date->format('Y-m-d');

            $query->where('DATE(created_on) <= :end')->bind(array('end' => $end));
        }

        if (!$this->getModel()->getTable()->getDriver()->execute($query)) {
            throw new Library\ControllerExceptionActionFailed('Delete Action Failed');
        } else {
            $context->status = Library\HttpResponse::NO_CONTENT;
        }
    }
}