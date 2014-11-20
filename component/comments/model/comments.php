<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Comments;

use Nooku\Library;
use Nooku\Library\DatabaseQuerySelect;

/**
 * Comments Model
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Nooku\Component\Comments
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