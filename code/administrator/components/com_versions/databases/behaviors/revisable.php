<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Database Revisable Behavior
 *
 * @author      Torkil Johnsen <torkil@bedre.no>
 * @author      Johan Janssens <johan@timble.net>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 */
class ComVersionsDatabaseBehaviorRevisable extends KDatabaseBehaviorAbstract
{
    /**
     * The versions_revisions table object
     *
     * @var KDatabaseTableDefault
     */
    protected $_table;
    
    /**
     * Constructor 
     * 
     * @param KConfig $config 
     */
    public function __construct(KConfig $config = null)
    {
        parent::__construct($config);

        foreach($config as $key => $value) {
            $this->{'_'.$key} = $value;
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(
            array('table' => KFactory::get('admin::com.versions.database.table.revisions'))
        );

        parent::_initialize($config);
    }
    
	/**
	 * Modify the select query
	 * 
	 * If the query's where information includes a 'trashed' propery, select all the trashed
	 * rows for this table.
	 * 
	 * @return false
	 */
	protected function _beforeTableSelect(KCommandContext $context)
	{
		$query = $context->query;
		
		if(!is_null($query)) 
		{
			foreach($query->where as $key => $where) 
			{
				if($where['property'] == 'tbl.trashed' && $where['value'] == 1) 
				{
					$table  = $context->caller;
					
					//Get the revisable model
					$identifier = clone($table->getIdentifier());
					$identifier->path[0] = 'model';
				
					$revisable = KFactory::get($identifier);
					
					//Get the revisions model
					$revisions = KFactory::get('admin::com.versions.model.revisions')
        							->status('deleted')
        							->row($revisable->get('id'))
        							->set($revisable->get())
      								->table($table->getName());

      				//Set the context data 
      				if(!$query->count) 
      				{			
      					$context->data = $table->getRowset()
      										->setData($revisions->getList()->data)
      										->setStatus(KDatabase::STATUS_LOADED);
      				}
      				else $context->data = $revisions->getTotal();
     			
      				return false;
				}
			}
		}
	}
	
    /**
     * Store a revision on insert
     *
     * Add a new revision of the row. It might seem unnecessary to store a revision for an item 
     * that was just created and has not been edited yet, but will prove useful in a context where 
     * multiple websites are using the same revision repository.
     *
     * @param   KCommandContext $context
     * @return  void
     */
    protected function _afterTableInsert(KCommandContext $context)
    { 
    	$row   = $context->data;
    	
    	if($this->_countRevisions($row, KDatabase::STATUS_INSERTED) == 0) {
    		$this->_insertRevision($row, KDatabase::STATUS_INSERTED);
    	}
    }
    
    /**
     * Before table update
     * 
     * Add a new revision if the row exists and it hasn't been revised yet. If the row was deleted 
     * revert it.
     *
     * @param  KCommandContext $context
     * @return void
     */
    protected function _beforeTableUpdate(KCommandContext $context)
    {
    	$row = $context->data;
    	
    	if($context->caller->count($row->id)) 
    	{
     		if ($this->_countRevisions($row) == 0) {
            	$this->_insertRevision($row, KDatabase::STATUS_INSERTED);
        	}
    	}
    	else 
    	{
    		if($this->_countRevisions($row, KDatabase::STATUS_DELETED) == 1) 
    		{
    			//Restore the row
    			$context->caller->getRow()->setData($row->getData())->save();
    			
    			//Set the row status to updated
    			$row->setStatus(KDatabase::STATUS_UPDATED);
    			
    			//Delete the revision
    			$this->_deleteRevisions($row, KDatabase::STATUS_DELETED);
    			
    			return false;
    		}
    	}
    }

    /**
     * After table update
     *
     * Add a new revision if the row was succesfully updated
     *
     * @param   KCommandContext $context
     * @return  void
     */
    protected function _afterTableUpdate(KCommandContext $context)
    {
    	$row = $context->data;
    	
    	// Only insert new revision if the database was updated
        if ((bool) $context->affected) {
            $this->_insertRevision($row, KDatabase::STATUS_UPDATED);
        }
    }
    
	/**
     * Before Table Delete
     * 
     * Add a new revision if the row exists and it hasn't been revised yet. Delete the revisions for 
     * the row, if the row was previously deleted.
     *
     * @param  KCommandContext $context
     * @return void
     */
    protected function _beforeTableDelete(KCommandContext $context)
    {
    	$row = $context->data;
    	
   		if ($context->caller->count($row->id)) 
   		{	
   			if($this->_countRevisions($row) == 0) {
           		 $this->_insertRevision($row, KDatabase::STATUS_INSERTED);
   			}
        } 
        else
        {
    	 	if($this->_countRevisions($row, KDatabase::STATUS_DELETED) == 1) 
    		{
    			$this->_deleteRevisions($row);	
    			return false;
    		}
        }
    }
    
  	/**
     * After Table Delete
     * 
     * After a row has been deleted, save the previously preseved data as revision
     * with status deleted.
     * 
     * @param  KCommandContext $context
     * @return void
     */
    protected function _afterTableDelete(KCommandContext $context)
    { 
    	$row = $context->data;
    	$this->_insertRevision($row, KDatabase::STATUS_DELETED);
    }

    /**
     * Insert a new revision
     *
     * @param  array    $data   Revision data
     * @return void
     */
    protected function _insertRevision(KDatabaseRowInterface $row, $status)
    {
    	$table = $row->getTable();
    	
    	// Get the row data
    	if ($status == KDatabase::STATUS_UPDATED) {
            $data = $row->getData(true);
        } else {
            $data = $row->getData();
        }
 
    	// Create the new revision
    	$revision = $this->_table->getRow();
    	$revision->table    = $table->getName();
        $revision->row      = $row->id;
        $revision->status   = $status;
        $revision->data     = (object) $table->filter($data);
         
    	// Set the created_on and created_by information based on the creatable
    	// or modifiable data in the row itself in cascading order
        if ($row->isCreatable())
    	{
            if(isset($row->created_by) && !empty($row->created_by)) {
                $revision->created_by  = $row->created_by;
            }

            if(isset($row->created_on) && ($row->created_on != $table->getDefault('created_on'))) {
                $revision->created_on = $row->created_on;
            }
    	}
          
        if ($row->isModifiable())
    	{
            if(isset($row->modified_by) && !empty($row->modified_by)) {
                $revision->created_by  = $row->modified_by;
            }

            if(isset($row->modified_on) && ($row->modified_on != $table->getDefault('modified_on'))) {
                $revision->created_on = $row->modified_on;
            }
    	}
    	
        // Store the revision
        $revision->save();
    }
    
 	/**
     * Find an existing revision
     *
     * @param  object 	A KDatabaseRowInterface object
     * @param  string   The row status to look for
     * @return	boolean
     */
    protected function _countRevisions(KDatabaseRowInterface $row, $status = null)
    {
    	$query = array(
           	'table'  => $row->getTable()->getName(),
            'row'    => $row->id
    	);
    	
    	if($status) {
    		$query['status'] = $status;
    	}
    		
    	return $this->_table->count($query);
    }
    
	/**
     * Delete one or all revisions for a row
     *
     * @param  object 	A KDatabaseRowInterface object
     * @param  string   The row status to look for
     * @return	boolean
     */
    protected function _deleteRevisions(KDatabaseRowInterface $row, $status = null)
    {
    	$query = array(
           	'table'  => $row->getTable()->getName(),
            'row'    => $row->id
    	);
    	
    	if($status) {
    		$query['status'] = $status;
    	}
    		
    	return $this->_table->select($query)->delete();
    }
}