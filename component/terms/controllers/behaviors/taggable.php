<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Terms;

use Nooku\Framework;

/**
 * Taggable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Terms
 */
class ControllerBehaviorTaggable extends Framework\BehaviorAbstract
{			
	protected function _saveRelations(Framework\CommandContext $context)
    {
		if ($context->error) {
			return;
		}
        
        $row   = $context->result;
        $table = $row->getTable()->getBase();
        
        // Remove all existing relations
        if($row->id && $row->getTable()->getBase())
        {
            $rows = $this->getService('com:terms.model.relations')
                ->row($row->id)
                ->table($table)
                ->getRowset();

            $rows->delete();
        }
        
        // Save terms as relations
		foreach ($row->terms as $term)
        {
			$relation = $this->getService('com:terms.database.row.relation');
            $relation->terms_term_id = $term;
            $relation->row		     = $row->id;
            $relation->table		 = $table;
    
            if(!$relation->load()) {
                $relation->save();
            }
		}
		
		return true;
	}
	
	protected function _afterControllerAdd(Framework\CommandContext $context)
    {
		$this->_saveRelations($context);
	}
	
	protected function _afterControllerEdit(Framework\CommandContext $context)
    {
		$this->_saveRelations($context);
	}
	
	protected function _afterControllerDelete(Framework\CommandContext $context)
    {
        $status = $context->result->getStatus();

        if($status == Framework\Database::STATUS_DELETED || $status == 'trashed')
        {
            $id = $context->result->get('id');
            $table = $context->result->getTable()->getBase();

            if(!empty($id) && $id != 0)
            {
                $rows = $this->getService('com:terms.model.relations')
                    ->row($id)
                    ->table($table)
                    ->getRowset();

                $rows->delete();
            }
        }
	} 
}