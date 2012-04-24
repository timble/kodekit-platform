<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Model Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksModelCategories extends ComDefaultModelDefault
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

		$query->columns(array('numlinks' => 'COUNT(weblinks.id)'));
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
		$query->join(array('weblinks' => 'weblinks'), 'weblinks.catid = tbl.id');
    }

	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
		parent::_buildQueryWhere($query);
		
		$query->where('tbl.section = :section')
            ->where('tbl.published = :published')
            ->where('weblinks.published = :weblinks_published')
            ->where('tbl.access <= :access');
            
        $query->bind(array(
            'section' => 'com_weblinks',
            'published' => 1,
            'weblinks_published' => 1,
            'access' => JFactory::getUser()->get('aid', '0')
        ));
    }
    
    protected function _buildQueryGroup(KDatabaseQuerySelect $query)
    {
		parent::_buildQueryGroup($query);
		
		$query->group('tbl.id');
    }

	protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
		parent::_buildQueryOrder($query);
		
		$query->order('tbl.ordering', 'DESC');
    }
}