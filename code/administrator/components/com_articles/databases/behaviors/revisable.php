<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Orderable Database Behavior Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesDatabaseBehaviorRevisable extends ComVersionsDatabaseBehaviorRevisable
{
 	/**
     * Select the revisions
     * 
     * This function will perform filtering of the revisions based on the category 
     * and section id if both are present in the where clause.
     *
     * @param  object   A database table object
     * @param  string   The row status
     * @param  array    Array of row id's
     * @return KDatabaseRowsetInterface
     */
    protected function _selectRevisions($table, $status, $where)
    {
        $result = parent::_selectRevisions($table, $status, $where);
        
        $needle = array();
        if(isset($where['tbl.catid'])) {
            $needle['category_id'] = $where['tbl.catid'];
        }
        
        if(isset($where['tbl.sectionid'])) {
            $needle['section_id'] = $where['tbl.sectionid'];
        }
        
        if(!empty($needle)) {
            $result = $result->find($needle);
        }
        
        return $result;
    }
}