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
 * Relations Model
 *   
 * @author   	Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Terms
 */
class ModelRelations extends Library\ModelTable
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->getState()
			->insert('row', 'int')
		 	->insert('table', 'string', $this->getIdentifier()->package);
	}
    
    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns(array(
            'title'   => 'terms.title',
            'slug'    => 'terms.slug'
        ));
	}
	 
	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
	{
        parent::_buildQueryJoins($query);
        
        $query->join(array('terms' => 'terms'), 'terms.terms_term_id = tbl.terms_term_id');
	}
	
	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
	{                
        $state = $this->getState();
        
        if(!$this->_state->isUnique()) 
		{
			if($this->_state->table) {
				$query->where('tbl.table = :table')->bind(array('table' => $this->_state->table));
			}
		
			if($this->_state->row) {
				$query->where('tbl.row IN :row')->bind(array('row' => (array) $this->_state->row));
			}
		}
        
        parent::_buildQueryWhere($query);
	}
}