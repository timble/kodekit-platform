<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Weblinks;

use Nooku\Library;

/**
 * Weblink Model
 *
 * @author  Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @package Nooku\Component\Weblinks
 */
class ModelWeblinks extends Library\ModelTable
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		$this->getState()
		    ->insert('category' , 'slug')
		    ->insert('published', 'boolean')
            ->insert('sort', 'cmd', 'ordering');
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

		if ($state->search) {
			$query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
		}
		
		if (is_bool($state->published)) {
			$query->where('tbl.published = :published')->bind(array('published' => (int) $state->published));
		}
		
	    if ($state->category) {
			$query->where('tbl.categories_category_id = :category')->bind(array('category' => (int) $state->category));
		}
	}
}