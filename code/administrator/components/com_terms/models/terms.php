<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComTermsModelTerms extends KModelTable
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->_state
			->insert('row', 'int')
		 	->insert('table', 'string');
	}
	
	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		if(!$this->_state->isUnique()) {
			$query->select('COUNT( relations.terms_term_id ) AS count');
		}
		
		return parent::_buildQueryColumns($query);
	}
	
	protected function _buildQueryGroup(KDatabaseQuery $query)
	{	
		if(!$this->_state->isUnique()) {
			$query->group('relations.terms_term_id');
		}
		
		return parent::_buildQueryGroup($query);
	}
	 
	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		if(!$this->_state->isUnique()) {
			$query->join('LEFT', 'terms_relations AS relations', 'relations.terms_term_id = tbl.terms_term_id');
		}
		
		return parent::_buildQueryJoins($query);
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		if(!$this->_state->isUnique()) 
		{
			if($this->_state->table) {
				$query->where('relations.table','=', $this->_state->table);
			}
		
			if($this->_state->row) {
				$query->where('relations.row', 'LIKE',  $this->_state->row);
			}
		}
		
		parent::_buildQueryWhere($query);
	}
}