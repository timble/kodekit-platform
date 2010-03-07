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
		// Delete a relation
		$rowset = KFactory::get('admin::com.terms.model.relations')
					->set(KRequest::get('request', 'string'))
					->getList()
					->delete();
						
		return $rowset;
	}
	
	protected function _actionAdd() 
	{			
		// Add a relation
		$row = KFactory::get('admin::com.terms.model.relations')
				->getItem()
				->setData(KRequest::get('post', 'raw'))
				->save();
							
		return $row;
	}
}