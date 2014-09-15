<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Comments;

use Nooku\Library\DatabaseQuerySelect;

/**
 * Comments Model
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Nooku\Component\Comments
 */
class CommentsModelComments extends Comments\ModelComments
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