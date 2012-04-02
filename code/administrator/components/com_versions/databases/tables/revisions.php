<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Revisions Table Class
 *
 * @author      Torkil Johnsen <http://nooku.assembla.com/profile/torkiljohnsen>
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Versions
 */
class ComVersionsDatabaseTableRevisions extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
    {     
        $config->append(array(
            'behaviors' => array('creatable')
        ));

        parent::_initialize($config);
    }

    /**
     * Insert a new row into the table
     * 
     * Takes care of automatically incrementing the revision number
     *
     * @param KDatabaseRowInterface $row
     */
    public function insert(KDatabaseRowInterface $row)
    {
    	$query = $this->getService('koowa:database.query.select')
            ->where('table', '=', $row->table)
            ->where('row',   '=', $row->row)
            ->order('revision','desc')
            ->limit(1);

       	$latest = $this->select($query, KDatabase::FETCH_ROW);

     	if (!$latest->isNew()) {
            $row->revision = $latest->revision + 1;
        } 

        return parent::insert($row);
    }
}