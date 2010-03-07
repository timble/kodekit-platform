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
	
    public function getItem()
    {
        if (!isset($this->_item))
        {	
        	$table = KFactory::get($this->getTable());
        	
        	if($this->_state->terms_term_id && $this->_state->table_name && $this->_state->row_id) {
        		$this->_item = parent::getItem();
        	} else  {
        		$this->_item =  KFactory::tmp($table->getRow(), array('table' => $table));
        	}
        }

        return $this->_item;
    }
}