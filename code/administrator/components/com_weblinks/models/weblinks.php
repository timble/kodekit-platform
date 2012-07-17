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
 * Weblink Model Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksModelWeblinks extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->getState()
		    ->insert('category' , 'slug')
		    ->insert('published', 'boolean');
	}
	
	protected function _buildQueryColumns(KDatabaseQuerySelect $query)
	{
		parent::_buildQueryColumns($query);

		$query->columns(array(
			'category_title' => 'categories.title',
			'editor' => 'users.name'
	    ));
	}

	protected function _buildQueryJoins(KDatabaseQuerySelect $query)
	{
		parent::_buildQueryJoins($query);

		$query->join(array('categories' => 'categories'), 'categories.id = tbl.catid')
			  ->join(array('users' => 'users'), 'users.id = tbl.checked_out');
	}
	
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
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
			$query->where('tbl.catid = :category')->bind(array('category' => (int) $state->category));
		}
	}
}