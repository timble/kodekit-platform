<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Newsfeeds Model Class
 *
 * @author      Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */
class ComNewsfeedsModelCategories extends ComDefaultModelDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'table' => 'admin::com.categories.database.table.categories'
        ));

        parent::_initialize($config);
    }
    
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);
        
        $query->where('tbl.section', '=', 'com_newsfeeds');
    }
    
    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $query->order('tbl.title', 'ASC');
    }
    
    public function getColumn($column)
    {   
        if (!isset($this->_column[$column])) 
        {   
            if($table = $this->getTable()) 
            {
                $query = $table->getDatabase()->getQuery()
                    ->distinct()
                    ->group('tbl.'.$table->mapColumns($column))
                    ->where('tbl.section', '=', 'com_newsfeeds');

                $this->_buildQueryOrder($query);
                        
                $this->_column[$column] = $table->select($query);
            }
        }
            
        return $this->_column[$column];
    }
}