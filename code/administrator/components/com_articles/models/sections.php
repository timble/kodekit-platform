<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sections Table Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles    
 */
class ComArticlesModelSections extends ComDefaultModelDefault
{	
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->getState()
			->insert('scope'	 , 'string', '')
			->insert('published' , 'boolean');

	}
	
	protected function _buildQueryColumns(KDatabaseQuerySelect $query)
	{
		parent::_buildQueryColumns($query);
		
		$query->columns(array(
			'categorycount',
			'activecount' => 'SUM(IF(active.state <> -2, 1, 0))'
		));
	}

	protected function _buildQueryJoins(KDatabaseQuerySelect $query)
	{
		//Exclude joins if counting records
		if (!$query->count) {
		    $subquery = $this->getService('koowa:database.query.select')
		        ->columns(array('section', 'categorycount' => 'COUNT(section)'))
		        ->table('categories')
		        ->where('published <> - 2')
		        ->group('section');
		    
		    $query->join(array('categories' => $subquery), 'categories.section = tbl.id')
		        ->join(array('active' => 'content'), 'active.sectionid = tbl.id');
		}
		
		parent::_buildQueryJoins($query);
	}
	
	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{
	    parent::_buildQueryWhere($query);
		$state = $this->getState();

		if($state->search) {
			$query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
		}

		if($state->scope) {
			$query->where('tbl.scope LIKE :scope')->bind(array('scope' => $state->scope));
		}
		
		if($state->published) {
			$query->where('tbl.published = :published')->bind(array('published' => (int) $state->published));
		}
	}
	
	protected function _buildQueryGroup(KDatabaseQuerySelect $query)
	{
		$query->group('tbl.id');
	}
}