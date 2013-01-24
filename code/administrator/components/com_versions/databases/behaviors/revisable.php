<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Database Revisable Behavior
 *
 * @author      Torkil Johnsen <http://nooku.assembla.com/profile/torkiljohnsen>
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Versions
 */
class ComVersionsDatabaseBehaviorRevisable extends KDatabaseBehaviorAbstract
{
    /**
     * The versions_revisions table object
     *
     * @var KDatabaseTableDefault
     */
    protected $_table = null;

    /**
     * Constructor
     *
     * @param KConfig $config
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        foreach($config as $key => $value) 
        {
            if(property_exists($this, '_'.$key)) { 
                $this->{'_'.$key} = $value;
            }
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
        $config->append(array(
        	'table' => $this->getService('com://admin/versions.database.table.revisions')
        ));

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

		if(!is_null($query) && $query->where)
		{
		    foreach ($query->where as $where) 
		    {
		        if (is_string($where['condition']) && preg_match('/(?:^|AND\s+)tbl\.deleted\s*=\s*(1|:[a-z_]+)/', $where['condition'], $matches)) 
		        {
		            if ($matches[1] == 1 || isset($query->params[substr($matches[1], 1)]) && $query->params[substr($matches[1], 1)] == 1) 
		            {
    		            $table = $context->getSubject();

          			    $revisions = $this->_selectRevisions($table, KDatabase::STATUS_DELETED, $query);

          			    if ($query->isCountQuery()) {
          			        $context->data = count($revisions);
          			    } 
          			    else 
          			    {
              			    $rowset = $table->getRowset();
        
                            foreach($revisions as $row) 
                            {
                                $options = array(
                    				'data'   => $row->data,
                        			'status' => 'trashed',
                            		'new'    => false,   
                                );
                                
                                $rowset->insert($rowset->getRow($options));
                            }
        
              			    $context->data = $rowset;
              		    }

              		    return false;
		            }
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
    	if($this->_countRevisions(KDatabase::STATUS_CREATED) == 0) {
    		$this->_insertRevision(KDatabase::STATUS_CREATED);
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
    	$table = clone $this->getTable();
    	if($table->count($this->id))
    	{
    	    if ($this->_countRevisions() == 0) {
            	$this->_insertRevision(KDatabase::STATUS_CREATED);
        	}
    	}
    	else
    	{
    	    if($this->_countRevisions(KDatabase::STATUS_DELETED) == 1)
    		{
    		    //Set the status
    		    $this->setStatus('restored');

    		    //Restore the row
    			$table->getRow()->setData($this->getData())->save();
    			
    			//Delete the revision
    			$context->affected = $this->_deleteRevisions(KDatabase::STATUS_DELETED);
    			
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
    	$table = clone $this->getTable();
   		if ($table->count($this->id))
   		{
   			if($this->_countRevisions() == 0) {
           		 $this->_insertRevision(KDatabase::STATUS_CREATED);
   			}
        }
        else
        {
    	 	if($this->_countRevisions(KDatabase::STATUS_DELETED) == 1)
    		{
                $context->affected = $this->_deleteRevisions();

                $this->setStatus(KDatabase::STATUS_DELETED);
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
    	
    	$this->setStatus('trashed');
    }
    
    /**
     * Select the revisions
     *
     * @param  object   A database table object
     * @param  string   The row status
     * @param  array    Array of row id's
     * @return KDatabaseRowsetInterface
     */
    protected function _selectRevisions($table, $status, $query)
    {
        $columns = array(
        	'table'  => $table->getName(),
            'status' => $status,
        );
        
        foreach ($query->where as $where) 
        {
            if (is_string($where['condition']) && preg_match('/(?:^|AND\s+)tbl\.'.preg_quote($table->getIdentityColumn()).'\s*=\s*(\d+|:[a-z_]+)/', $where['condition'], $matches)) 
            {
                if (is_numeric($matches[1])) 
                {
                    $columns['row'] = (int) $matches[1];
                    break;
                } 
                elseif (isset($query->params[substr($matches[1], 1)])) 
                {
                    $columns['row'] = (int) $query->params[substr($matches[1], 1)];
                    break;
                }
            }
        }
          
        $revisions = $this->_table->select($columns);
        return $revisions;
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
    	$revision->table    = $table->getBase();
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
    	
    	// Set revision number.
    	if ($status == KDatabase::STATUS_UPDATED || $status == KDatabase::STATUS_DELETED) 
    	{
    	    $query = $this->getService('koowa:database.query.select')
        	    ->where('table = :table')
        	    ->where('row = :row')
        	    ->order('revision', 'DESC')
        	    ->bind(array(
        	    	'table' => $table->getBase(),
        	        'row'   => $this->id
        	    ));
        	
        	$revision->revision = $this->_table->select($query, KDatabase::FETCH_ROW)->revision + 1;
    	}

        // Store the revision
        $revision->save();
    }

 	/**
     * Find an existing revision
     *
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