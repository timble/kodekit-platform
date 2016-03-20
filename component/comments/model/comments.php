<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-comments for the canonical source repository
 */

namespace Kodekit\Component\Comments;

use Kodekit\Library;
use Kodekit\Library\DatabaseQuerySelect;

/**
 * Comments Model
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Kodekit\Component\Comments
 */
class ModelComments extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('table', 'string', $this->getIdentifier()->package)
            ->insert('row', 'int')
            ->insert('search', 'cmd');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('searchable'),
        ));

        parent::_initialize($config);
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);

        if (!$this->getState()->isUnique())
        {
            $state = $this->getState();

            if ($state->table) {
                $query->where('tbl.table = :table')->bind(array('table' => $state->table));
            }

            if ($state->row) {
                $query->where('tbl.row = :row')->bind(array('row' => $state->row));
            }
        }
    }
}