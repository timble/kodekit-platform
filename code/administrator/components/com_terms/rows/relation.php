<?php
/**
 * @version		$Id: terms.php 308 2009-10-25 04:35:37Z johan $
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComTermsRowRelation extends KDatabaseRowAbstract
{
	/**
     * Save the term in the database.
     *
     * If the term does not exist yet it will be created. A relationship for 
     * the term will also be added to the terms_relations table based on the
     * row_id and table_name information.
     *
     * @return	TermsRowTerm
     */
    public function save()
    {
    	// Check if term exists, if not then add a new term
    	$table  = KFactory::get('admin::com.terms.table.terms');
    	$query  = $table->getDatabase()->getQuery();
    	$term   = $table->fetchRow($query->where('name', '=', $this->_data['name']));
       			
		if(!$term->id) 
		{
			$term->name = $this->_data['name'];
			$term->save();
		}
		
		// Set the term id for this relation
		$this->terms_term_id = $term->id;
		
		// Save the relation
		parent::save();
	
        return $this;
    }
    
	/**
     * Deletes the term form the database.
     *
     * @return TermsRowTerm
     */
    public function delete()
    {
    	// If not other relations exists, delete the term
    	$query  = $this->_table->getDatabase()->getQuery();
       	$result = $this->_table->count($query->where('terms_term_id', '=', $this->terms_term_id));
       	 	
		if($result == 1) 
		{
			KFactory::get('admin::com.terms.table.terms')
				->fetchRow($this->terms_term_id)
				->delete();
		}
		
		// Delete the relation
    	parent::delete();
    	
        return $this;
    }
}