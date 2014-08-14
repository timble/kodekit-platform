<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Flat Orderable Database Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorOrderableFlat extends DatabaseBehaviorOrderableAbstract implements DatabaseBehaviorOrderableInterface
{
    protected function _beforeInsert(Library\DatabaseContext $context)
    {
        $query = $this->getObject('lib:database.query.select')
            ->columns('MAX(tbl.ordering)');
        
        $this->_buildQuery($query);
        
        $max = (int) $context->getSubject()->select($query, Library\Database::FETCH_FIELD);
        $context->data->ordering = $max + 1;
    }
    
    protected function _beforeUpdate(Library\DatabaseContext $context)
    {
        $row = $context->data;
        if($row->order)
        {
			$old = (int) $row->ordering;
			$new = $row->ordering + $row->order;
			$new = $new <= 0 ? 1 : $new;

			$table = $context->getSubject();
			$query = $this->getObject('lib:database.query.update')
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
    
    protected function _afterUpdate(Library\DatabaseContext $context)
    {
        if($context->affected === false) {
            $this->_reorder($context);
        }
    }
    
    protected function _afterDelete(Library\DatabaseContext $context)
    {
        if($context->affected) {
            $this->_reorder($context);
        }
    }
    
    protected function _buildQuery($query)
    {
        if(!$query instanceof Library\DatabaseQuerySelect && !$query instanceof Library\DatabaseQueryUpdate) {
	        throw new \InvalidArgumentException('Query must be an instance of Library\DatabaseQuerySelect or Library\DatabaseQueryUpdate');
	    }

        $identifier = $this->getMixer()->getIdentifier();
        if($identifier == 'module' && $identifier->package == 'pages') {
            $query->where('position = :position')->bind(array('position' => $this->position));
        }
    }
    
    protected function _reorder(Library\DatabaseContext $context)
    {
        $table = $context->getSubject();
        $table->getAdapter()->execute('SET @index = 0');

        $query = $this->getObject('lib:database.query.update')
            ->table(array('tbl' => $table->getBase()))
            ->values('ordering = (@index := @index + 1)')
            ->order('ordering', 'ASC');
        
        $this->_buildQuery($query);
        
        $table->getAdapter()->update($query);
    }
}