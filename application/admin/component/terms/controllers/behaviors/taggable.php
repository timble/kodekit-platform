<?php

class ComTermsControllerBehaviorTaggable extends KControllerBehaviorAbstract
{			
	protected function _saveRelations(KCommandContext $context) {
		if ($context->error) {
			return;
		}
        
        $row   = $context->result;
        $table = $row->getTable()->getBase();
        
        // Remove all existing relations
        if($row->id && $row->getTable()->getBase())
        {
            $rows = $this->getService('com://admin/terms.model.relations')
                ->row($row->id)
                ->table($table)
                ->getRowset();

            $rows->delete();
        }
        
        // Save terms as relations
		foreach ($row->terms as $term) {
			$relation = $this->getService('com://admin/terms.database.row.relation');
            $relation->terms_term_id = $term;
            $relation->row		     = $row->id;
            $relation->table		 = $table;
    
            if(!$relation->load()) {
                $relation->save();
            }
		}
		
		return true;
	}
	
	protected function _afterControllerAdd(KCommandContext $context) {
		$this->_saveRelations($context);
	}
	
	protected function _afterControllerEdit(KCommandContext $context) {
		$this->_saveRelations($context);
	}
	
	protected function _afterControllerDelete(KCommandContext $context)
    {
        $status = $context->result->getStatus();

        if($status == KDatabase::STATUS_DELETED || $status == 'trashed')
        {
            $id = $context->result->get('id');
            $table = $context->result->getTable()->getBase();

            if(!empty($id) && $id != 0)
            {
                $rows = $this->getService('com://admin/terms.model.relations')
                    ->row($id)
                    ->table($table)
                    ->getRowset();

                $rows->delete();
            }
        }
	} 
}