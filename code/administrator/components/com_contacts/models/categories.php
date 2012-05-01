<?php
/**
 * @version		$Id: categories.php 3542 2012-04-02 18:27:01Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
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
 * @subpackage  Contacts
 */
class ComContactsModelCategories extends ComDefaultModelDefault
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
    
        $query->columns(array('numlinks' => 'COUNT(contacts.id)'));
    }
    
    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);
    
        $query->join(array('contacts' => 'contacts_contacts'), 'contacts.catid = tbl.id');
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
    
        $query->where('tbl.section = :section')
              ->where('tbl.published = :published')
              ->where('contacts.published = :contacts_published')
              ->where('tbl.access <= :access');
    
        $query->bind(array(
    			'section'            => 'com_contacts_contacts',
    		    'published'          => 1,
    		    'contacts_published' => 1,
    		    'access'             => JFactory::getUser()->get('aid', '0')
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