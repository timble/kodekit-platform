<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComTermsModelRelations extends KModelTable
{
	public function __construct($options = array())
	{
		parent::__construct($options);
		
		// Set the state
		$this->_state
		 	->insert('terms_term_id', 'int')
		 	->insert('table_name', 'string')
		 	->insert('row_id', 'int');
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
         		$query = $this->_buildQuery()
         						->where('tbl.terms_term_id', '=', $this->_state->terms_term_id, 'AND')
         						->where('tbl.table_name', '=', $this->_state->table_name, 'AND')
         						->where('tbl.row_id', '=', $this->_state->row_id);
        		$this->_item = $table->fetchRow($query);
        	} 
        	else $this->_item = null;
        }

        return parent::getItem();
    }
}