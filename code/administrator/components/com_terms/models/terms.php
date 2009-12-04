<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class comTermsModelTerms extends KModelTable
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
	
	/**
     * Get a tag object
     *
     * @return KDatabaseRow
     */
    public function getItem()
    {
        // Get the data if it doesn't already exist
        if (!isset($this->_item))
        {
        	if($table = $this->getTable()) 
        	{
         		$query = $this->_buildQuery()->where('tbl.name', '=', $this->_state->name);
        		$this->_item = $table->fetchRow($query);
        	} 
        	else $this->_item = null;
        }

        return parent::getItem();
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
		$state = $this->_state;
		
		if($state->tags_tag_id) {
			$query->where('relations.terms_term_id','=', $state->tags_tag_id);
		}
		
		if($state->row_id) {
			$query->where('relations.row_id', 'LIKE',  $state->row_id);
		}

		if($state->table_name) {
			$query->where('relations.table_name','=', $state->table_name);
		}
	}
}