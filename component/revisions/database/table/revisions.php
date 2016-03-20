<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-revisions for the canonical source repository
 */

namespace Kodekit\Component\Revisions;

use Kodekit\Library;

/**
 * Revisions Database Table
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Revisions
 */
class DatabaseTableRevisions extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'      => 'revisions',
            'behaviors' => array('creatable', 'identifiable'),
            'filters'   => array(
                'data' => array('json')
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Insert a new row into the table
     *
     * Takes care of automatically incrementing the revision number
     *
     * @param Library\DatabaseRowInterface $row
     */
    public function insert(Library\DatabaseRowInterface $row)
    {
    	$query = $this->getObject('lib:database.query.select')
            ->where('table', '=', $row->table)
            ->where('row',   '=', $row->row)
            ->order('revision','desc')
            ->limit(1);

       	$latest = $this->select($query, Library\Database::FETCH_ROW);

     	if (!$latest->isNew()) {
            $row->revision = $latest->revision + 1;
        }

        return parent::insert($row);
    }
}