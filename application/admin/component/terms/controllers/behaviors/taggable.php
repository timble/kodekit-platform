<?php

class ComTermsControllerBehaviorTaggable extends KControllerBehaviorAbstract
{	
	/**
	 * Controller to handle term saving
	 */
	protected $_term_controller = null;
	
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_populate_from_request = $config->populate_from_request;
        
        $this->_term_controller = $this->getService($config->term_controller, array(
			'request' => $this->getService('lib://nooku/controller.request', array(
				'query' => array()
			))
		));
	}
	
    protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'term_controller' => 'com://admin/terms.controller.term',
		));
		
		parent::_initialize($config);
	}
	
	protected function _saveTerm(KCommandContext $context, $term)
	{
		$row = $context->result;
		
		try {			
			$data = $this->_term_controller->add(array(
				'id' => $term,
				'row' => $row->id,
				'table' => $row->getTable()->getBase()
			
			));
			
			$this->_term_controller->getModel()->reset(false);
		}
		catch (KControllerException $e) {
			$context->response->setStatus($e->getCode() , $e->getMessage());
			return false;
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