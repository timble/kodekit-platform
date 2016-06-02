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
 * Activities Model.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
 */
class ModelActivities extends Library\ModelDatabase
{
    /**
     * Constructor.
     *
     * @param ObjectConfig $config Configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $state = $this->getState();

        $state->insert('application', 'cmd')
              ->insert('type', 'cmd')
              ->insert('package', 'cmd')
              ->insert('name', 'cmd')
              ->insert('action', 'cmd')
              ->insert('row', 'int')
              ->insert('user', 'cmd')
              ->insert('start_date', 'date')
              ->insert('end_date', 'date')
              ->insert('day_range', 'int')
              ->insert('ip', 'ip');
    }

    /**
     * Builds WHERE clause for the query.
     *
     * @param Library\DatabaseQueryInterface $query
     */
    protected function _buildQueryWhere(Library\DatabaseQueryInterface $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();

        if ($state->application) {
            $query->where('tbl.application = :application')->bind(array('application' => $state->application));
        }

        if ($state->type) {
            $query->where('tbl.type = :type')->bind(array('type' => $state->type));
        }

        if ($state->package) {
            $query->where('tbl.package = :package')->bind(array('package' => $state->package));
        }

        if ($state->name) {
            $query->where('tbl.name = :name')->bind(array('name' => $state->name));
        }

        if ($state->action) {
            $query->where('tbl.action IN (:action)')->bind(array('action' => $state->action));
        }

        if (is_numeric($state->row)) {
            $query->where('tbl.row IN (:row)')->bind(array('row' => $state->row));
        }

        if ($state->start_date && $state->start_date != '0000-00-00')
        {
            $start_date = $this->getObject('lib:date',array('date' => $state->start_date));

            $query->where('DATE(tbl.created_on) >= :start')->bind(array('start' => $start_date->format('Y-m-d')));

            if (is_numeric($state->day_range)) {
                $query->where('DATE(tbl.created_on) <= :range_start')->bind(array('range_start' => $start_date->modify(sprintf('+%d days', $state->day_range))->format('Y-m-d')));
            }
        }

        if ($state->end_date && $state->end_date != '0000-00-00')
        {
            $end_date  = $this->getObject('lib:date',array('date' => $state->end_date));

            $query->where('DATE(tbl.created_on) <= :end')->bind(array('end' => $end_date->format('Y-m-d')));

            if (is_numeric($state->day_range)) {
                $query->where('DATE(tbl.created_on) >= :range_end')->bind(array('range_end' => $end_date->modify(sprintf('-%d days', $state->day_range))->format('Y-m-d')));
            }
        }

        if ($state->user) {
            $query->where('tbl.created_by = :created_by')->bind(array('created_by' => $state->user));
        }

        if ($ip = $state->ip) {
            $query->where('tbl.ip IN (:ip)')->bind(array('ip' => $state->ip));
        }
    }
}
