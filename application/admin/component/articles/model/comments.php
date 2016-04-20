<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Comments;

use Kodekit\Library;
use Kodekit\Component\Comments;

/**
 * Comments Model
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Kodekit\Platform\Comments
 */
class ModelComments extends Comments\ModelComments
{
    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array(
            'title' => 'table.title'
        ));
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        $state  = $this->getState();
        $column = $this->getObject('com:' . $state->table . '.database.table.' . $state->table)->getIdentityColumn();

        $query->join(array('table' => 'articles'), 'table.' . $column . ' = tbl.row');
    }
}