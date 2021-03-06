<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-tags for the canonical source repository
 */

namespace Kodekit\Component\Tags;

use Kodekit\Library;

/**
 * Tags Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Tags
 */
class ModelTags extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('row', 'cmd')
            ->insert('created_by', 'int');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('searchable'),
        ));

        parent::_initialize($config);
    }

    final public function getTable()
    {
        if(!($this->_table instanceof Library\DatabaseTableInterface)) {
            $this->_table = $this->getObject('com:tags.database.table.tags', array('name' => $this->_table));
        }

        return $this->_table;
    }

    final public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array(
            'count' => 'COUNT( relations.tag_id )'
        ));

        if($this->getState()->row)
        {
            $query->columns(array(
                'row' => 'relations.row'
            ));
        }
    }

    protected function _buildQueryGroup(Library\DatabaseQuerySelect $query)
    {
        $query->group('tbl.slug');
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        $table = $this->getTable()->getName();
        $query->join(array('relations' => $table.'_relations'), 'relations.tag_id = tbl.tag_id');
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        if($state->row) {
            $query->where('relations.row IN :row')->bind(array('row' => (array) $this->getState()->row));
        }
        
        if ($state->created_by)
        {
            $query->where('tbl.created_by IN :created_by')
                ->bind(array('created_by' => (array) $state->created_by));
        }

        parent::_buildQueryWhere($query);
    }
}
