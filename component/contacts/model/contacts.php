<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Contacts;

use Nooku\Library;

/**
 * Contacts Model
 *
 * @author  Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Contacts
 */
class ModelContacts extends Library\ModelTable
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);
		
		$this->getState()
			->insert('published', 'boolean')
			->insert('category' , 'slug')
			->insert('access'   , 'int')
            ->insert('sort'     , 'cmd', 'ordering');
	}

	protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
	{
		parent::_buildQueryColumns($query);
		
		$query->columns(array(
			'category_title' => 'categories.title'
		));
	}

	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
	{
		parent::_buildQueryJoins($query);
		
		$query->join(array('categories' => 'categories'), 'categories.categories_category_id = tbl.categories_category_id');
	}

	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
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
		    $query->where('tbl.access <= :access')->bind(array('access' => $state->access));
		}
	}
}