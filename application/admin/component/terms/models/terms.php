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
		$this->getState()
			->insert('row', 'int')
		 	->insert('table', 'string', $this->getIdentifier()->package);
	}
	
	protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns(array(
            'count'    => 'COUNT( relations.terms_term_id )'
        ));
	}
	
	protected function _buildQueryGroup(KDatabaseQuerySelect $query)
	{	
        $query->group('relations.terms_term_id');
	}
	 
	protected function _buildQueryJoins(KDatabaseQuerySelect $query)
	{
        parent::_buildQueryJoins($query);
        
        $query->join(array('relations' => 'terms_relations'), 'relations.terms_term_id = tbl.terms_term_id');
	}
	
	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{                
        if(!$this->_state->isUnique()) 
		{
			if($this->_state->table) {
				$query->where('relations.table = :table')->bind(array('table' => $this->_state->table));
			}
		
			if($this->_state->row) {
				$query->where('relations.row IN :row')->bind(array('row' => (array) $this->_state->row));
			}
		}
        
        parent::_buildQueryWhere($query);
	}
}