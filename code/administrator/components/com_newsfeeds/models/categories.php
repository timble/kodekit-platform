<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Model Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */
class ComNewsfeedsModelCategories extends ComDefaultModelDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'table' => 'com://admin/categories.database.table.categories'
        ));

        parent::_initialize($config);
    }
    
    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
		parent::_buildQueryColumns($query);

		$query->select('COUNT(newsfeeds.id) AS numlinks');
    }

	protected function _buildQueryGroup(KDatabaseQuerySelect $query)
    {
		parent::_buildQueryGroup($query);
		
		$query->group('tbl.id');
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
		parent::_buildQueryJoins($query);
		
		$query->join('LEFT', 'newsfeeds AS newsfeeds', 'newsfeeds.catid = tbl.id');
    }

	protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
		parent::_buildQueryOrder($query);
		
		$query->order('tbl.ordering', 'DESC');
    }

	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
		parent::_buildQueryWhere($query);
		
		$query->where('tbl.section', '=', 'com_newsfeeds')
			  ->where('tbl.published', '=', '1')
			  ->where('newsfeeds.published', '=', '1')
			  ->where('tbl.access', '<=', JFactory::getUser()->get('aid', '0'));
    }
}