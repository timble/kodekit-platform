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
 * Purgeable Controller Behavior.
 *
 * Adds purge action to the controller.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
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

        if (!$this->getModel()->getTable()->getAdapter()->execute($query)) {
            throw new Library\ControllerExceptionActionFailed('Delete Action Failed');
        } else {
            $context->status = Library\HttpResponse::NO_CONTENT;
        }
    }
}