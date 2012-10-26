<?php
class ComPagesDatabaseBehaviorOrderableFlat extends ComPagesDatabaseBehaviorOrderableAbstract/* implements ComPagesDatabaseBehaviorOrderableInterface*/
{
    protected function _beforeTableInsert(KCommandContext $context)
    {
        $query = $this->getService('koowa:database.query.select')
            ->columns('MAX(ordering)');
        
        $this->_buildQueryWhere($query);
        
        $max = (int) $context->getSubject()->select($query, KDatabase::FETCH_FIELD);
        $context->data->ordering = $max + 1;
    }
    
    protected function _beforeTableUpdate(KCommandContext $context)
    {
        $data = $context->data;
        if($data->order)
        {        
			$old = (int) $data->ordering;
			$new = $data->ordering + $data->order;
			$new = $new <= 0 ? 1 : $new;

			$table = $context->getSubject();
			$query = $this->getService('koowa:database.query.update')
			    ->table($table->getBase());
			
			$this->_buildQueryWhere($query);

			if($data->order < 0) 
			{
			    $query->values('ordering = ordering + 1')
			        ->where('ordering >= :new')
			        ->where('ordering < :old')
			        ->bind(array('new' => $new, 'old' => $old));
			} 
			else 
			{
			    $query->values('ordering = ordering - 1')
			        ->where('ordering > :old')
			        ->where('ordering <= :new')
			        ->bind(array('new' => $new, 'old' => $old));
			}
			
			$table->getDatabase()->update($query);
			$data->ordering = $new;
        }
    }
    
    protected function _afterTableUpdate(KCommandContext $context)
    {
        if($context->affected === false) {
            $this->_reorder();
        }
    }
    
    protected function _afterTableDelete(KCommandContext $context)
    {
        if($context->affected) {
            $this->_reorder();
        }
    }
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        if(!$query instanceof KDatabaseQuerySelect && !$query instanceof KDatabaseQueryUpdate) {
	        throw new InvalidArgumentException('Query must be an instance of KDatabaseQuerySelect or KDatabaseQueryUpdate');
	    }
    }
    
    protected function _reorder()
    {
        $table = $context->getSubject();
        $table->getDatabase()->execute('SET @order = 0');
        
        $query = $this->getService('koowa:database.query.update')
            ->table($table->getBase())
            ->values('ordering = (@order := @order + 1)')
            ->order('ordering', 'ASC');
        
        $this->_buildQueryWhere($query);
        
        $table->getDatabase()->update($query);
    }
}