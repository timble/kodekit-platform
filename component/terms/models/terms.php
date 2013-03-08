<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Terms Model
 *   
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Terms
 */
class ComTermsModelTerms extends ComDefaultModelDefault
{
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->_state
			->insert('row', 'int')
		 	->insert('table', 'string');
	}
	
	protected function _buildQueryColumns(Framework\DatabaseQuery $query)
	{
		if(!$this->_state->isUnique()) {
			$query->select('COUNT( relations.terms_term_id ) AS count');
			$query->select('table');
		}
		
		return parent::_buildQueryColumns($query);
	}
	
	protected function _buildQueryGroup(Framework\DatabaseQuery $query)
	{	
		if(!$this->_state->isUnique()) {
			$query->group('relations.terms_term_id');
		}
		
		return parent::_buildQueryGroup($query);
	}
	 
	protected function _buildQueryJoins(Framework\DatabaseQuery $query)
	{
		if(!$this->_state->isUnique()) {
			$query->join('LEFT', 'terms_relations AS relations', 'relations.terms_term_id = tbl.terms_term_id');
		}
		
		return parent::_buildQueryJoins($query);
	}
	
	protected function _buildQueryWhere(Framework\DatabaseQuery $query)
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