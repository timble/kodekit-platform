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
 * Taggable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Tags
 */
class ControllerBehaviorTaggable extends Library\BehaviorAbstract
{			
	protected function _saveRelations(Library\CommandContext $context)
    {
		if ($context->error) {
			return;
		}
        
        $row   = $context->result;
        $table = $row->getTable()->getBase();
        
        // Remove all existing relations
        if($row->id && $row->getTable()->getBase())
        {
            $rows = $this->getObject('com:tags.model.relations')
                ->row($row->id)
                ->table($table)
                ->getRowset();

            $rows->delete();
        }

        if($row->tags)
        {
            // Save tags as relations
		    foreach ($row->tags as $tag)
            {
			    $relation = $this->getObject('com:tags.database.row.relation');
                $relation->tags_tag_id = $tag;
                $relation->row		  = $row->id;
                $relation->table      = $table;
    
                if(!$relation->load()) {
                    $relation->save();
                }
		    }
        }
		
		return true;
	}
	
	protected function _afterControllerAdd(Library\CommandContext $context)
    {
		$this->_saveRelations($context);
	}
	
	protected function _afterControllerEdit(Library\CommandContext $context)
    {
		$this->_saveRelations($context);
	}
	
	protected function _afterControllerDelete(Library\CommandContext $context)
    {
        $status = $context->result->getStatus();

        if($status == Library\Database::STATUS_DELETED || $status == 'trashed')
        {
            $id = $context->result->get('id');
            $table = $context->result->getTable()->getBase();

            if(!empty($id) && $id != 0)
            {
                $rows = $this->getObject('com:tags.model.relations')
                    ->row($id)
                    ->table($table)
                    ->getRowset();

                $rows->delete();
            }
        }
	} 
}