<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComTermsControllerTerm extends KControllerBread
{	
	public function __construct(array $options = array())
	{
		parent::__construct($options);

		//Prevent state from being saved
		$this->unregisterFunctionAfter('browse'  , 'saveState');
	}
	
	protected function _actionDelete() 
	{		
		// Get the relation id to be deleted
		$ids = (array) KRequest::get('post.terms_relation_id', 'int');
		
		// Delete the relations
		$rowset = KFactory::get('admin::com.terms.table.relations')
					  ->fetchRowset($ids)
					  ->delete();
		
		$row_id 	 = KRequest::get('post.row_id', 'int');
		$table_name  = KRequest::get('post.table_name', 'cmd');
		
		//$this->_redirect = 'view=terms&row_id='.$row_id.'&table_name='.$table_name;
		//@TODO : Ajax requests shouldn't redirect, while normal requests should
		$this->execute('read');
		
		return $rowset;
	}
	
	protected function _actionAdd() 
	{
		// Get term data
		$data = KRequest::get('post', 'string');
		
		// Add a relation
		$rows = KFactory::get('admin::com.terms.table.relations')
					  ->fetchRow()
					  ->setData($data)
					  ->save();
	
		$row_id 	 = KRequest::get('post.row_id', 'int');
		$table_name  = KRequest::get('post.table_name', 'cmd');
		
		//$this->_redirect = 'view=terms&row_id='.$row_id.'&table_name='.$table_name;
		//@TODO : Ajax requests shouldn't redirect, while normal requests should
		$this->execute('read');
	}
}