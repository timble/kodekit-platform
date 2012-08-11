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
 * Assignable Database Behavior Class
 *
 * Provides ordering support for closure tables by using a special ordering help of another table
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesDatabaseBehaviorOrderable extends KDatabaseBehaviorAbstract
{
    protected $_columns = array();
    
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        if($config->columns) {
            $this->setColumns(KConfig::unbox($config->columns));
        }
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'   => KCommand::PRIORITY_LOWEST,
            'auto_mixin' => true,
            'columns'    => array()
        ));

        parent::_initialize($config);
    }
    
    public function setColumns(array $columns = array())
    {
        $this->_columns = array_unique(array_merge($this->_columns, $columns));
    }
    
    public function getColumns()
    {
        return $this->_columns;
    }
    
    protected function _afterTableInsert(KCommandContext $context)
    {       
        if($context->data->getStatus() != KDatabase::STATUS_FAILED) {
            $this->_reorder($context);
        }
    }
    
    protected function _afterTableUpdate(KCommandContext $context)
    {       
        if($context->data->getStatus() != KDatabase::STATUS_FAILED) {
            $this->_reorder($context);
        }
    }
    
    protected function _afterTableDelete(KCommandContext $context)
    {       
        if($context->data->getStatus() != KDatabase::STATUS_FAILED) {
            $this->_reorder($context);
        }
    }
    
    protected function _reorder(KCommandContext $context)
    {
        if($context->data->getStatus() != KDatabase::STATUS_FAILED)
        {
            // Get siblings.
            $data     = $context->data;
            $siblings = $data->getSiblings();
            
            // Get orderings.
            $identifier = $this->getTable()->getIdentifier();
            $identifier->name = substr($this->getTable()->getOrderingTable(), strlen($identifier->package) + 1);
            
            $table = $this->getService($identifier);
            $orderings = $table->select(count($siblings) ? $siblings->id : null, KDatabase::FETCH_ROWSET);
            
            switch($context->operation)
            {
                case KDatabase::OPERATION_INSERT:
                    $current = $table->getRow(array('data' => array('id' => $data->id)));
                    break;
                    
                case KDatabase::OPERATION_UPDATE:
                    $current = $table->select($data->id, KDatabase::FETCH_ROW);
                    break;
            }
            
            // Iterate trough the columns.
            foreach($this->_columns as $column)
            {
                // Call _reorder{Column} method if exists. 
                $method = '_reorder'.ucfirst($column);
                if(!method_exists($this, $method))
                {
                    $list = array_combine($siblings->id, $siblings->$column);
                    if($context->operation != KDatabase::OPERATION_DELETE) {
                        $list[$data->id] = $data->$column;
                    }
                }
                else $list = $this->$method($context, $siblings, $orderings);
                
                natcasesort($list);
                $list = array_flip(array_keys($list));
                
                foreach($orderings as $ordering) {
                    $ordering->$column = $list[$ordering->id] + 1;
                }
                
                if($context->operation != KDatabase::OPERATION_DELETE) {
                    $current->$column = $list[$data->id] + 1;
                }
            }
            
            $orderings->save();
            
            if($context->operation != KDatabase::OPERATION_DELETE) {
                $current->save();
            }
        }
    }
    
    protected function _reorderCustom(KCommandContext $context, KDatabaseRowsetInterface $siblings, KDatabaseRowsetInterface $orderings)
    {
        $data = $context->data;
        $list = array_combine($orderings->id, $orderings->custom);
        
        switch($context->operation)
        {
            case KDatabase::OPERATION_INSERT:
            {
                if($data->ordering)
                {
                    // If ordering is set, increase value of elements that are after the number.
                    $ordering = (int) $data->ordering;
                    foreach($list as &$item)
                    {
                        if($item >= $ordering) {
                            $item++;
                        }
                    }
                }
                else $ordering = max($orderings->custom) + 1;
                
                $list[$data->id] = $ordering;
            } break;
                
            case KDatabase::OPERATION_UPDATE:
            {
                // Modify ordering with the change.
                $ordering = $data->order ? $data->ordering + $data->order : $data->ordering;
                $ordering = $ordering <= 0 ? 1 : $ordering;
                
                foreach($list as &$item)
                {
                    if($data->order < 0 && $item >= $ordering && $item < $data->ordering) {
                        $item++;
                    } elseif($data->order > 0 && $item > $data->ordering && $item <= $ordering) {
                        $item--;
                    }
                }
                
                $list[$data->id] = $ordering;
            } break;
        }
        
        return $list;
    }
    
    public function getMixableMethods(KObject $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);
        unset($methods['setColumns']);
        unset($methods['getColumns']);

        return $methods;
    }
}
