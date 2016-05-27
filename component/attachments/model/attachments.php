<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-attachments for the canonical source repository
 */

namespace Kodekit\Component\Attachments;

use Kodekit\Library;

/**
 * Attachments Model
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Kodekit\Component\Attachments
 */
class ModelAttachments extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('row', 'int')
            ->insert('table', 'string');
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        if (!$this->getState()->isUnique())
        {
            $query->columns(array('count' => 'COUNT(relations.attachments_attachment_id)'))
                ->columns('table')
                ->columns('row');
        }

        parent::_buildQueryColumns($query);
    }

    protected function _buildQueryGroup(Library\DatabaseQuerySelect $query)
    {
        if (!$this->getState()->isUnique()) {
            $query->group('relations.attachments_attachment_id');
        }

        return parent::_buildQueryGroup($query);
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        if (!$this->getState()->isUnique()) {
            $query->join(array('relations' => 'attachments_relations'), 'relations.attachments_attachment_id = tbl.attachments_attachment_id', 'LEFT');
        }

        parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        if (!$this->getState()->isUnique()) {
            if ($this->getState()->table) {
                $query->where('relations.table = :table')->bind(array('table' => $this->getState()->table));
            }

            if ($this->getState()->row) {
                $query->where('relations.row IN :row')->bind(array('row' => (array)$this->getState()->row));
            }
        }

        parent::_buildQueryWhere($query);
    }
}