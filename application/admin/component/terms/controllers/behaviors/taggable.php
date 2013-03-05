<?php

class ComTermsControllerBehaviorTaggable extends KControllerBehaviorAbstract
{		
	protected function _saveTerm(KCommandContext $context, $term)
	{
		$row = $context->result;

        $relation = $this->getService('com://admin/terms.database.row.relation');
        $relation->terms_term_id = $term;
        $relation->row		   = $row->id;
        $relation->table		 = $row->getTable()->getBase();

        if(!$relation->load()) {
            $relation->save();
        }
		
		return true;
	}
	
	protected function _saveTerms(KCommandContext $context) {
		if ($context->error) {
			return;
		}
        
        $row = $context->result;
        
		foreach ($row->terms as $term) {
			$this->_saveTerm($context, $term);
		}
		
		return true;
	}
	
	protected function _afterControllerAdd(KCommandContext $context) {
		$this->_saveTerms($context);
	}
	
	protected function _afterControllerEdit(KCommandContext $context) {
		$this->_saveTerms($context);
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