<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Menus Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesModelMenus extends KModelTable
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	}
	
    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->select('COUNT(page.id) AS page_count');
    }
    
    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
         parent::_buildQueryJoins($query);
         
         $query->join('LEFT', 'menu AS page', 'tbl.menutype = page.menutype');
    }
    
    protected function _buildQueryGroup(KDatabaseQuery $query)
    {
        parent::_buildQueryGroup($query);

        $query->group('id');
    }

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;

		if ($state->search)
		{
			$search = '%'.$state->search.'%';
			$query->where('tbl.title', 'LIKE',  $search);
		}
		
		parent::_buildQueryWhere($query);
	}
}