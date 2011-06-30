<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Contacts Model Class
 *
 * @author      Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts   
 */

class ComContactsModelContacts extends KModelTable
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
		
		$query->select('categories.title AS category_title');
		$query->select('user.name AS username');
	}

	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		parent::_buildQueryJoins($query);
		
		$query->join('LEFT', 'categories AS categories', 'categories.id = tbl.catid');
		$query->join('LEFT', 'users AS user', 'user.id = tbl.user_id');
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