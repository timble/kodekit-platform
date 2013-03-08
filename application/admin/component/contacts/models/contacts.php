<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Contacts Model Class
 *
 * @author      Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts   
 */

class ComContactsModelContacts extends Framework\ModelTable
{
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);
		
		$this->getState()
			->insert('published', 'boolean')
			->insert('category' , 'slug')
			->insert('access', 'int')
            ->insert('sort', 'cmd', 'ordering');
	}

	protected function _buildQueryColumns(Framework\DatabaseQuerySelect $query)
	{
		parent::_buildQueryColumns($query);
		
		$query->columns(array(
			'category_title' => 'categories.title'
		));
	}

	protected function _buildQueryJoins(Framework\DatabaseQuerySelect $query)
	{
		parent::_buildQueryJoins($query);
		
		$query->join(array('categories' => 'categories'), 'categories.categories_category_id = tbl.categories_category_id');
	}

	protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
	{
	    parent::_buildQueryWhere($query);
		$state = $this->getState();
		
		if (is_bool($state->published)) {
			$query->where('tbl.published = :published')->bind(array('published' => (int) $state->published));
		}

		if ($state->category) {
			$query->where('tbl.categories_category_id = :category')->bind(array('category' => (int) $state->category));
		}

		if ($state->search) {
			$query->where('tbl.name LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
		}
		
		if (is_numeric($state->access)) {
		    $query->where('tbl.access = :access')->bind(array('access' => $state->access));
		}
	}
}