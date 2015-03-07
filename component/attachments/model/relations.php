<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Attachments Relations Model
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Nooku\Component\Attachments
 */
class ModelRelations extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('row', 'int')
            ->insert('table', 'string');
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        if ($this->getState()->table) {
            $query->where('tbl.table = :table')->bind(array('table' => $this->getState()->table));
        }

        if ($this->getState()->row) {
            $query->where('tbl.row IN :row')->bind(array('row' => (array)$this->getState()->row));
        }

        parent::_buildQueryWhere($query);
    }
}