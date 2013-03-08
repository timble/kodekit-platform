<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Weblink Model Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksModelWeblinks extends ComDefaultModelDefault
{
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		$this->getState()
		    ->insert('category' , 'slug')
		    ->insert('published', 'boolean')
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