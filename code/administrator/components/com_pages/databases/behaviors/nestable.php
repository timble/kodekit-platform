<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Nestable Database Behavior Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseBehaviorNestable extends KDatabaseBehaviorAbstract
{
    protected $_table;
    
    public function __construct(KConfig $config = null)
    {
        parent::__construct($config);

        foreach($config as $key => $value) {
            $this->{'_'.$key} = $value;
        }
    }

    protected function _initialize(KConfig $config)
    {
        $config->append(
            array('table' => null)
        );

        parent::_initialize($config);
    }
    
    protected function _beforeTableSelect(KCommandContext $context)
    { 
    	if($context->mode == KDatabase::FETCH_ROWSET)
    	{
    		$this->_table = $context->caller;
    		
    		$this->_table->getDatabase()->getCommandChain()
				->enqueue($this, $this->getPriority());
    	}
    }
    
	protected function _afterTableSelect(KCommandContext $context)
    {
    	if(isset($this->_table))
    	{
    		$this->_table->getDatabase()->getCommandChain()
				->dequeue($this);
			
			$this->_table = null;
    	}
    }
	
	protected function _beforeAdapterSelect(KCommandContext $context)
  	{
  		$context->limit  = $context->query->limit;
  		$context->offset = $context->query->offset; 
  		
  		$context->query->limit(0);
  	}
  	
  	protected function _afterAdapterSelect(KCommandContext $context)
  	{
  		//Get the data
  		$rows = KConfig::unbox($context->result);
  		
  		if(is_array($rows))
  		{	
  			$children = array();
  			$result = array();
  			
  			/*
  			 * Create the children array
  			 */
    		foreach($rows as $key => $row)
			{
				$path   = array();
				$parent = $row['parent_id'];
				
				//Store node by parent
				if(!empty($parent) && isset($rows[$parent])) {		
					$children[$parent][] = $key;
				}
			}
			
			/*
			 * Create the result array 
			 */
  			foreach($rows as $key => $row)
			{
				if(empty($row['parent_id'])) 
				{
					$result[$key] = $row;
					
					if(isset($children[$key])) {
						$this->_getChildren($rows, $children, $key, $result);
					}
				}
			}
			
			/*
			 * If we have not been able to match all children to their parents don't perform
			 * the path enumeration for the children.
			 */
			if(count($result) == count($rows)) 
			{
				if($context->limit) {
					$result = array_slice( $result, $context->offset, $context->limit, true);
				}
			
				/*
			 	 * Create the paths of each node
			 	 */
  				foreach($result as $key => $row)
				{
					$path   = array();
					$parent = $row['parent_id'];
				
					if(!empty($parent)) 
					{
						$table  = $this->_table;
					
						//Create node path
						$path = $result[$parent]['path'];
						$id   = $result[$parent][$table->getIdentityColumn()];
				
						$path[] = $id;
					}

					//Set the node path
					$result[$key]['path'] = $path;	
				}
			}
			else $result = $rows;
			
			$context->result = $result;
  		}
  	}
  	
  	protected function _getChildren($rows, $children, $parent, &$result)
  	{					
  		foreach($children[$parent] as $child)
		{
			//Add the child to the rows
			$result[$child] = $rows[$child];
			
			if(isset($children[$child])) {
				$this->_getChildren($rows, $children, $child, $result);
			}
		}
  	}
}