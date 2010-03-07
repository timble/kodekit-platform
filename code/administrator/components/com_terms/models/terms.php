<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComTermsModelTerms extends KModelTable
{
	public function __construct($options = array())
	{
		parent::__construct($options);
		
		// Set the state
		$this->_state
		 	->insert('terms_term_id', 'int')
			->insert('row_id', 'int')
		 	->insert('name', 'string')
		 	->insert('table_name', 'string');
	}
	 
	protected function _buildQueryFields(KDatabaseQuery $query)
	{
		$query->select('tbl.*')
			  ->select('relations.terms_relation_id');
	}

	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		$query->join('LEFT', 'terms_relations AS relations', 'relations.terms_term_id = tbl.terms_term_id');
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$query->where('relations.row_id', 'LIKE',  $this->_state->row_id);
		
		if($this->_state->tags_tag_id) {
			$query->where('relations.terms_term_id','=', $this->_state->tags_tag_id);
		}
		
		if($this->_state->table_name) {
			$query->where('relations.table_name','=', $this->_state->table_name);
		}
	}
}