<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Database Revisable Behavior
 *
 * @author      Torkil Johnsen <torkil@bedre.no>
 * @author      Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Versions
 */
class ComVersionsDatabaseBehaviorRevisable extends KDatabaseBehaviorAbstract
{
    /**
	 * Table object or identifier (APP::com.COMPONENT.table.NAME)
	 *
	 * @var	string|object
	 */
	protected $_table = false;
    
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
        
        $this->setTable($config->table);
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
            array('table' => 'admin::com.versions.database.table.revisions')
        );

        parent::_initialize($config);
    }
    
 	/**
     * Method to get a table object
     * 
     * Function catches KDatabaseTableExceptions that are thrown for tables that 
     * don't exist. If no table object can be created the function will return FALSE.
     *
     * @return KDatabaseTableAbstract
     */
    public function getTable()
    {
        if($this->_table !== false)
        {
            if(!($this->_table instanceof KDatabaseTableAbstract))
		    {   		        
		        //Make sure we have a table identifier
		        if(!($this->_table instanceof KIndentifier)) {
		            $this->setTable($this->_table);
			    }
		        
		        try {
		            $this->_table = KFactory::get($this->_table);
                } catch (KDatabaseTableException $e) {
                    $this->_table = false;
                }
            }
        }

        return $this->_table;
    }

    /**
     * Method to set a table object attached to the model
     *
     * @param   mixed   An object that implements KObjectIdentifiable, an object that
     *                  implements KIndentifierInterface or valid identifier string
     * @throws  KDatabaseRowsetException    If the identifier is not a table identifier
     * @return  KModelTable
     */
    public function setTable($table)
	{
		if(!($table instanceof KDatabaseTableAbstract))
		{
			if(is_string($table) && strpos($table, '.') === false ) 
		    {
		        $identifier         = clone $this->_identifier;
		        $identifier->path   = array('database', 'table');
		        $identifier->name   = KInflector::tableize($table);
		    }
		    else  $identifier = KFactory::identify($table);
		    
			if($identifier->path[1] != 'table') {
				throw new KDatabaseRowsetException('Identifier: '.$identifier.' is not a table identifier');
			}

			$table = $identifier;
		}

		$this->_table = $table;

		return $this;
	}
	
	/**
	 * Test the connected status of the row.
	 *
	 * @return	boolean	Returns TRUE if we have a reference to a live KDatabaseTableAbstract object.
	 */
    public function isConnected()
	{
	    return (bool) $this->getTable();
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
					$table  = $this->getTable();
					
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
    	if($this->_countRevisions(KDatabase::STATUS_INSERTED) == 0) {
    		$this->_insertRevision(KDatabase::STATUS_INSERTED);
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
    	if($this->getTable()->count($this->id)) 
    	{
     		if ($this->_countRevisions() == 0) {
            	$this->_insertRevision(KDatabase::STATUS_INSERTED);
        	}
    	}
    	else 
    	{
    		if($this->_countRevisions(KDatabase::STATUS_DELETED) == 1) 
    		{
    			//Restore the row
    			$this->getTable()->getRow()->setData($this->getData())->save();
    			
    			//Set the row status to updated
    			$this->setStatus(KDatabase::STATUS_UPDATED);
    			
    			//Delete the revision
    			$this->_deleteRevisions(KDatabase::STATUS_DELETED);
    			
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
    	// Only insert new revision if the database was updated
        if ((bool) $context->affected) {
            $this->_insertRevision(KDatabase::STATUS_UPDATED);
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
   		if ($this->getTable()->count($this->id)) 
   		{	
   			if($this->_countRevisions() == 0) {
           		 $this->_insertRevision(KDatabase::STATUS_INSERTED);
   			}
        } 
        else
        {
    	 	if($this->_countRevisions(KDatabase::STATUS_DELETED) == 1) 
    		{
    			$this->_deleteRevisions();	
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
    	$this->_insertRevision(KDatabase::STATUS_DELETED);
    }

    /**
     * Insert a new revision
     *
     * @param  array    $data   Revision data
     * @return void
     */
    protected function _insertRevision($status)
    {
    	$table = $this->getTable();
    	
    	// Get the row data
    	if ($status == KDatabase::STATUS_UPDATED) {
            $data = $this->getData(true);
        } else {
            $data = $this->getData();
        }
        
    	// Create the new revision
    	$revision = $this->_table->getRow();
    	$revision->table    = $table->getName();
        $revision->row      = $this->id;
        $revision->status   = $status;
        $revision->data     = (object) $table->filter($data);
         
    	// Set the created_on and created_by information based on the creatable
    	// or modifiable data in the row itself in cascading order
        if($this->isCreatable())
    	{
            if(isset($this->created_by) && !empty($this->created_by)) {
                $revision->created_by  = $this->created_by;
            }

            if(isset($this->created_on) && ($this->created_on != $table->getDefault('created_on'))) {
                $revision->created_on = $this->created_on;
            }
    	}
          
        if ($this->isModifiable())
    	{
            if(isset($this->modified_by) && !empty($this->modified_by)) {
                $revision->created_by  = $this->modified_by;
            }

            if(isset($this->modified_on) && ($this->modified_on != $table->getDefault('modified_on'))) {
                $revision->created_on = $this->modified_on;
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
    protected function _countRevisions($status = null)
    {
    	$query = array(
           	'table'  => $this->getTable()->getName(),
            'row'    => $this->id
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
    protected function _deleteRevisions($status = null)
    {
    	$query = array(
           	'table'  => $this->getTable()->getName(),
            'row'    => $this->id
    	);
    	
    	if($status) {
    		$query['status'] = $status;
    	}
    		
    	return $this->_table->select($query)->delete();
    }
}