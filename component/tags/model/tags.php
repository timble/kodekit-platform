<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Tags Model
 *   
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Tags
 */
class ModelTags extends Library\ModelDatabase
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);
		
		// Set the state
		$this->getState()
            ->insert('row'  , 'int')
            ->insert('table', 'string', $this->getIdentifier()->package);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('searchable'),
        ));

        parent::_initialize($config);
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns(array(
            'count' => 'COUNT( relations.tags_tag_id )'
        ));
	}
	
	protected function _buildQueryGroup(Library\DatabaseQuerySelect $query)
	{	
        $query->group('tbl.tags_tag_id');
	}
	 
	protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
	{
        parent::_buildQueryJoins($query);
        
        $query->join(array('relations' => 'tags_relations'), 'relations.tags_tag_id = tbl.tags_tag_id');
	}
	
	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
	{                
        $state = $this->getState();

        if($this->getState()->row) {
            $query->where('relations.row IN :row')->bind(array('row' => (array) $this->getState()->row));
        }

        if($this->getState()->table) {
            $query->where('tbl.table = :table')->bind(array('table' => $this->getState()->table));
        }
        
        parent::_buildQueryWhere($query);
	}
}