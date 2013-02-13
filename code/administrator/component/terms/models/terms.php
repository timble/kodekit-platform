<?php
/**
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Terms
 * @copyright	Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Description
 *   
 * @author   	Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Terms
 */
class ComTermsModelTerms extends ComDefaultModelDefault
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
			$query->select('table');
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