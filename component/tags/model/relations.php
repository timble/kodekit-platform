<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Relations Model
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Tags
 */
class ModelRelations extends Library\ModelTable
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->getState()
			->insert('row'  , 'int')
		 	->insert('table', 'string', $this->getIdentifier()->package);
	}
    
    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns(array(
            'title'   => 'tags.title',
            'slug'    => 'tags.slug'
        ));
	}
	 
	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
	{
        parent::_buildQueryJoins($query);
        
        $query->join(array('tags' => 'tags'), 'tags.tags_tag_id = tbl.tags_tag_id');
	}
	
	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
	{                
        $state = $this->getState();
        
        if(!$this->getState()->isUnique())
		{
			if($this->getState()->table) {
				$query->where('tbl.table = :table')->bind(array('table' => $this->getState()->table));
			}
		
			if($this->getState()->row) {
				$query->where('tbl.row IN :row')->bind(array('row' => (array) $this->getState()->row));
			}
		}
        
        parent::_buildQueryWhere($query);
	}
}