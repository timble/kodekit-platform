<?php
/**
 * @version    	$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Categories
 * @copyright  	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license    	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link       	http://www.nooku.org
 */

/**
 * Contacts Model Class
 *
 * @author		Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Categories    
 */
class ComContactModelContacts extends KModelTable
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state
			->insert('enabled', 'int')
			->insert('category', 'int');
	}

	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		parent::_buildQueryColumns($query);
		
		$query->select('categories.title AS category');
		$query->select('users.name AS user');
		$query->select('v.name AS editor');
	}

	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		parent::_buildQueryJoins($query);
		
		$query->join('LEFT', 'categories AS categories', 'categories.id = tbl.catid');
		$query->join('LEFT', 'users AS users', 'users.id = tbl.user_id');
		$query->join('LEFT', 'users AS v', 'v.id = tbl.checked_out');
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;
		
		if (is_numeric($state->enabled)) {
			$query->where('tbl.published', '=', $state->enabled);
		}

		if (is_numeric($state->category) && !empty($state->category)) {
			$query->where('tbl.catid', '=', $state->category);
		}

		if ($state->search) {
			$search = '%'.$state->search.'%';
			$query->where('tbl.name', 'LIKE', $search);
		}
        
		parent::_buildQueryWhere($query);
	}
}