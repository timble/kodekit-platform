<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class TermsControllerTerm extends KControllerBread
{	
	public function __construct(array $options = array())
	{
		parent::__construct($options);

		//Prevent state from being saved
		$this->unregisterFilterAfter('browse'  , 'filterSaveState');
	}
	
	protected function _actionDelete() 
	{		
		$row_id 	 = KRequest::get('post.row_id', 'int');
		$term_id 	 = KRequest::get('post.terms_term_id', 'int');
		$relation_id = KRequest::get('post.terms_relation_id', 'int');
		$table_name  = KRequest::get('post.table_name', 'string');
		$format 	 = KRequest::get('get.format', 'string');
	
		//Delete the term relation
		KRequest::set('post.id', $relation_id);
		KFactory::tmp('admin::com.terms.controller.relation')->execute('delete');
			
		// Check for other relations of this term, if none then delete the term
		if(!KFactory::get('admin::com.terms.model.terms')->set('terms_term_id', $term_id)->getTotal()){
			KRequest::set('post.id', $term_id);
			parent::_actionDelete();
		}
		
		$this->_redirect  = 'view=terms&format='.$format.'&row_id='.$row_id.'&table_name='.$table_name;
	}
	
	protected function _actionAdd() 
	{
		$row_id 	= KRequest::get('post.row_id', 'int');
		$table_name = KRequest::get('post.table_name', 'string');
		$format 	= KRequest::get('get.format', 'string');
		
		// Get existing Tag ID
		$term_id = KFactory::tmp('admin::com.terms.model.terms')
						->set('name', KRequest::get('post.name', 'string'))->getItem()->id;
																	
		// Check if term exists, if not then add a new terms and use the id for storing in relations table
		if(!$term_id) {
			$term_id = parent::_actionAdd()->id;
		}
		
		// Check for existing Map ID
		$relation_id = KFactory::tmp('admin::com.terms.model.relations')
						->set('terms_term_id', $term_id)
						->set('table_name', $table_name)
						->set('row_id', $row_id)->getItem()->id;
						
		// Add relation
		if(!$relation_id)
		{
			KRequest::set('post.id', false);
			KRequest::set('post.terms_term_id', $term_id);
			KFactory::tmp('admin::com.terms.controller.relation')->execute('add');
		}
	
		$this->_redirect = 'view=terms&format='.$format.'&row_id='.$row_id.'&table_name='.$table_name;
	}
}