<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Revisable Database Behavior Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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
     * @param  object   A KDatabaseQuerySelect object.
     * @return KDatabaseRowsetInterface
     */
    protected function _selectRevisions($table, $status, $query)
    {
        $result = parent::_selectRevisions($table, $status, $query);
        $needle = array();
        
        // Filter by category id if set in the query.
        foreach ($query->where as $where) 
        {
            if (is_string($where['condition']) && preg_match('/(?:^|AND\s+)tbl\.categories_category_id\s*=\s*(\d+|:[a-z_]+)/', $where['condition'], $matches))
            {
                if (is_numeric($matches[1])) 
                {
                    $needle['category_id'] = (int) $matches[1];
                    break;
                } 
                elseif (isset($query->params[substr($matches[1], 1)])) 
                {
                    $needle['category_id'] = (int) $query->params[substr($matches[1], 1)];
                    break;
                }
            }
        }

        if($needle) {
            $result = $result->find($needle);
        }
        
        return $result;
    }
}