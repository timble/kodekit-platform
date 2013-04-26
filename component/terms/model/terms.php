<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Terms;

use Nooku\Library;

/**
 * Terms Model
 *   
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Terms
 */
class ModelTerms extends Library\ModelTable
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->getState()
		 	->insert('table', 'string', $this->getIdentifier()->package)
            ->insert('search', 'string');
	}
	
	protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns(array(
            'count'    => 'COUNT( relations.terms_term_id )'
        ));
	}
	
	protected function _buildQueryGroup(Library\DatabaseQuerySelect $query)
	{	
        $query->group('tbl.terms_term_id');
	}
	 
	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
	{
        parent::_buildQueryJoins($query);
        
        $query->join(array('relations' => 'terms_relations'), 'relations.terms_term_id = tbl.terms_term_id');
	}
	
	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
	{                
        $state = $this->getState();

        if($state->search) {
            $query->where('tbl.title LIKE :search')->bind(array('search' => '%' . $state->search . '%'));
        }
        
        if($this->_state->table) {
            $query->where('tbl.table = :table')->bind(array('table' => $this->_state->table));
        }
        
        parent::_buildQueryWhere($query);
	}
}