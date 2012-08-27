<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Tables Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */
class ComLanguagesModelTables extends ComDefaultModelDefault implements KServiceInstantiatable
{
    protected $_list_cache;
    
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->getState()
            ->insert('enabled', 'boolean')
            ->insert('component', 'int');
    }
    
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        if(!$container->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);
        }
        
        return $container->get($config->service_identifier);
    }
    
    public function getItem()
     {
        if(!$this->_list_cache) {
            $this->_list_cache = $this->getTable()->select();
        }
        
        $state  = $this->getState();
        return $state->isUnique() ? $this->_list_cache->find($state->id) : $this->_list_cache->getRow();
    }
    
    public function getList()
    {
        if(!$this->_list_cache) {
            $this->_list_cache = $this->getTable()->select($this->getService('koowa:database.query.select'));
        }
        
        $list  = $this->_filterList();
        $total = count($list);
        
        $state = $this->getState();
        if($limit = $state->limit)
        {
            $offset = $state->offset;
            $total  = $this->_total;
            
            if($offset && $total)        
            {
                if($offset >= $total) 
                {
                    $offset = floor(($total - 1) / $limit) * $limit;    
                    $state->offset = $offset;
                }
             }
             
             $counter = 1;
             foreach(clone $list as $item)
             {
                 if($counter <= $offset || $counter > $offset + $limit) {
                     $list->extract($item);
                 }
                 
                 $counter++;
             }
        }
        
        $this->_list  = $list;
        $this->_total = count($this->_list);
        
        return $this->_list;
    }
    
    protected function _filterList()
    {
        $state   = $this->getState();
        $filters = array();
        
        if(!is_null($state->enabled)) {
            $filters['enabled'] = (int) $state->enabled;
        }
        
        if($state->component) {
            $filters['components_component_id'] = $state->component;
        }
        
        return $filters ? $this->_list_cache->find($filters) : clone $this->_list_cache;
     }
}