<?php
/**
 * @version     $Id: pages.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Flat Orderable Database Behavior Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseBehaviorOrderableFlat extends ComPagesDatabaseBehaviorOrderableAbstract implements ComPagesDatabaseBehaviorOrderableInterface
{
    protected function _beforeTableInsert(KCommandContext $context)
    {
        $query = $this->getService('koowa:database.query.select')
            ->columns('MAX(ordering)');
        
        $this->_buildQuery($query);
        
        $max = (int) $context->getSubject()->select($query, KDatabase::FETCH_FIELD);
        $context->data->ordering = $max + 1;
    }
    
    protected function _beforeTableUpdate(KCommandContext $context)
    {
        $row = $context->data;
        if($row->order)
        {        
			$old = (int) $row->ordering;
			$new = $row->ordering + $row->order;
			$new = $new <= 0 ? 1 : $new;

			$table = $context->getSubject();
			$query = $this->getService('koowa:database.query.update')
			    ->table($table->getBase());
			
			$this->_buildQuery($query);

			if($row->order < 0)
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
			
			$table->getAdapter()->update($query);
			$row->ordering = $new;
        }
    }
    
    protected function _afterTableUpdate(KCommandContext $context)
    {
        if($context->affected === false) {
            $this->_reorder($context);
        }
    }
    
    protected function _afterTableDelete(KCommandContext $context)
    {
        if($context->affected) {
            $this->_reorder($context);
        }
    }
    
    protected function _buildQuery(KDatabaseQuerySelect $query)
    {
        if(!$query instanceof KDatabaseQuerySelect && !$query instanceof KDatabaseQueryUpdate) {
	        throw new InvalidArgumentException('Query must be an instance of KDatabaseQuerySelect or KDatabaseQueryUpdate');
	    }
    }
    
    protected function _reorder(KCommandContext $context)
    {
        $table = $context->getSubject();
        $table->getAdapter()->execute('SET @index = 0');

        $query = $this->getService('koowa:database.query.update')
            ->table($table->getBase())
            ->values('ordering = (@index := @index + 1)')
            ->order('ordering', 'ASC');
        
        $this->_buildQuery($query);
        
        $table->getAdapter()->update($query);
    }
}