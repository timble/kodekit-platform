<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sections Table Model Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections    
 */
class ComSectionsModelSections extends ComDefaultModelDefault
{	
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state
		 	->insert('search'	 , 'string')
			->insert('scope'	 ,'string')
			->insert('published' ,'int');

	}
	
	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		parent::_buildQueryColumns($query);
		$query->select('categorycount')
			->select('SUM( IF(active.state <> -2,1,0)) activecount')
			->select('SUM( IF(active.state = -2,1,0)) trashcount');
	}

	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		//Exclude joins if counting records
		if(!$query->count)
		{
			$query->join[]=array(
				'type' => 'LEFT',
				'table' => '(SELECT section, COUNT(section) categorycount FROM #__categories 
					WHERE published <> -2 GROUP BY section) AS cat', 
				'condition' => array('cat.section = tbl.id'));
			
			$query->join('LEFT','content AS active','active.sectionid = tbl.id');
		}
		
		parent::_buildQueryJoins($query);
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;

		if($state->search) {
			$query->where('tbl.title', 'LIKE',  '%'.$state->search.'%');
		}

		if($state->scope) {
			$query->where('tbl.scope', 'LIKE',  $state->scope);
		}
		
		if(is_numeric($state->published)) {
			$query->where('tbl.published', '=', $state->published);
		}
	      
		parent::_buildQueryWhere($query);
	}
	
	protected function _buildQueryGroup(KDatabaseQuery $query)
	{
		$query->group('tbl.id');
	}
}