<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Versions;

use Nooku\Library;

/**
 * Revisions Database Table
 *
 * @author  Torkil Johnsen <http://nooku.assembla.com/profile/torkiljohnsen>
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Versions
 */
class DatabaseTableRevisions extends Library\DatabaseTableDefault
{
    protected function _initialize(Library\ObjectConfig $config)
    {     
        $config->append(array(
            'behaviors' => array('creatable'),
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