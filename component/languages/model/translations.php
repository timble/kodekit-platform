<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Translations Model
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Languages
 */
class ModelTranslations extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('table', 'cmd')
            ->insert('row', 'int')
            ->insert('iso_code', 'com:languages.filter.iso')
            ->insert('status', 'int')
            ->insert('deleted', 'boolean', false);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('searchable'),
        ));

        parent::_initialize($config);
    }

    protected function _actionFetch(Library\ModelContext $context)
    {
        $state = $context->state;

        if ($state->sort == 'table')
        {
            $direction = strtoupper($state->direction);

            $context->query->order('tbl.row', $direction);
            $context->query->order('tbl.original', 'DESC');
        }

        parent::_actionFetch($context);
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if (!$state->isUnique()) {
            if ($state->table) {
                $query->where('tbl.table = :table')->bind(array('table' => $state->table));
            }

            if ($state->row) {
                $query->where('tbl.row = :row')->bind(array('row' => $state->row));
            }

            if ($state->iso_code) {
                $query->where('tbl.iso_code = :iso_code')->bind(array('iso_code' => $state->iso_code));
            }

            if (!is_null($state->status)) {
                $query->where('tbl.status = :status')->bind(array('status' => $state->status));
            }

            if (!is_null($state->deleted)) {
                $query->where('tbl.deleted = :deleted')->bind(array('deleted' => (int)$state->deleted));
            }
        }
    }
}