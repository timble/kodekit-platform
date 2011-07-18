<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Categories Model Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersModelCategories extends ComDefaultModelDefault 
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
        
        $query->where('tbl.section', '=', 'com_banner');
    }
    
    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $query->order('tbl.title', 'ASC');
    }
}