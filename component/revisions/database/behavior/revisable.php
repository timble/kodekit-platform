<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Revisions;

use Nooku\Library;

/**
 * Revisable Database Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Revisions
 */
class DatabaseBehaviorRevisable extends Library\DatabaseBehaviorAbstract
{
    /**
     * The revisions table object
     *
     * @var Library\DatabaseTableInterface
     */
    protected $_table;

    /**
     * Constructor
     *
     * @param Library\ObjectConfig $config
     */
    public function __construct(Library\ObjectConfig $config)
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
     * @param   Library\ObjectConfig  $config An optional Library\ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	'table' => $this->getObject('com:revisions.database.table.revisions')
        ));

        parent::_initialize($config);
    }

    /**
     * Command handler
     *
     * This function translates the command name to a command handler function of the format '_before[Command]' or
     * '_after[Command]. Command handler functions should be declared protected.
     *
     * @param     string                    $name       The command name
     * @param     Library\CommandContext    $context    The command context
     * @return    boolean   Can return both true or false.
     */
    public function execute($name, Library\CommandContext $context)
    {
        $parts = explode('.', $name);
        if($parts[0] == 'after')
        {
            if ($context->data instanceof Library\DatabaseRowInterface) {
                $this->setMixer(clone $context->data);
            }
        }

        return Library\BehaviorAbstract::execute($name, $context);
    }

	/**
	 * Before table select
	 *
	 * If a 'deleted' query param exsist, select all the trashed rows for this table and return them, instead of
     * performing a normal query.
	 *
	 * @return void|false
	 */
	protected function _beforeTableSelect(Library\CommandContext $context)
	{
        $query = $context->query;

        if($context->query->params->has('deleted'))
        {
            $table     = $context->getSubject();
            $revisions = $this->_selectRevisions($table, Library\Database::STATUS_DELETED, $query);

            if (!$query->isCountQuery())
            {
                $rowset = $table->getRowset();

                foreach($revisions as $row)
                {
                    $options = array(
                        'data'   => $row->data,
                        'status' => 'trashed',
                    );

                    $rowset->insert($rowset->getRow($options));
                }

                $context->data = $rowset;
            }
            else $context->data = count($revisions);

            return false;
        }
	}

    /**
     * After table insert
     *
     * Add a new revision of the row. We store a revision for a row that was just created to be able to create a
     * diff history later.
     *
     * @param   Library\CommandContext $context
     * @return  void
     */
    protected function _afterTableInsert(Library\CommandContext $context)
    {
        if($this->_countRevisions(Library\Database::STATUS_CREATED) == 0) {
    		$this->_insertRevision();
    	}
    }

    /**
     * Before table update
     *
     * Add a new revision if the row exists and it hasn't been revised yet. If the row was deleted revert it.
     *
     * @param  Library\CommandContext $context
     * @return void
     */
    protected function _beforeTableUpdate(Library\CommandContext $context)
    {
    	if(!$context->getSubject()->count($context->data->id))
    	{
            $this->setMixer($context->data);

            if($this->_countRevisions(Library\Database::STATUS_DELETED) == 1)
            {
                //Restore the row
                $table = clone $context->getSubject();
                $table->getCommandChain()->disable();
                $table->getRow()->setData($this->getData())->save();

                //Set the status
                $context->data->setStatus('restored');

                //Delete the revision
                $context->affected = $this->_deleteRevisions(Library\Database::STATUS_DELETED);

                //Prevent the item from being updated
                return false;
            }
    	}
    	else
    	{
            if ($this->_countRevisions() == 0) {
                $this->_insertRevision();
            }
    	}
    }

    /**
     * After table update
     *
     * Add a new revision if the row was succesfully updated
     *
     * @param   Library\CommandContext $context
     * @return  void
     */
    protected function _afterTableUpdate(Library\CommandContext $context)
    {
        // Only insert new revision if the database was updated
        if ((bool) $context->affected) {
            $this->_insertRevision();
        }
    }

	/**
     * Before table delete
     *
     * Add a new revision if the row exists and it hasn't been revised yet. Delete the revisions for the row, if the
     * row was previously deleted.
     *
     * @param  Library\CommandContext $context
     * @return void
     */
    protected function _beforeTableDelete(Library\CommandContext $context)
    {
   		if (!$context->getSubject()->count($context->data->id))
   		{
            $this->setMixer($context->data);

            if($this->_countRevisions(Library\Database::STATUS_DELETED) == 1)
            {
                //Delete the revision
                $context->affected = $this->_deleteRevisions();

                //Set the status
                $context->data->setStatus(Library\Database::STATUS_DELETED);

                //Prevent the item from being deleted
                return false;
            }
        }
        else
        {
            if($this->_countRevisions() == 0) {
                $this->_insertRevision();
            }
        }
    }

  	/**
     * After table delete
     *
     * After a row has been deleted, save the previously preseved data as revision with status deleted.
     *
     * @param  Library\CommandContext $context
     * @return void
     */
    protected function _afterTableDelete(Library\CommandContext $context)
    {
    	//Insert the revision
        $this->_insertRevision();

        //Set the status
    	$context->data->setStatus('trashed');
    }
    
    /**
     * Select revisions
     *
     * @param  object  $table   A database table object
     * @param  string  $status  The row status
     * @param  Library\DatabaseQueryInterface  $query   A database query object
     * @return Library\DatabaseRowsetInterface
     */
    protected function _selectRevisions($table, $status, Library\DatabaseQueryInterface $query)
    {
        $columns = array(
        	'table'  => $table->getName(),
            'status' => $status,
        );

        if($query->params->has($table->getIdentityColumn())) {
            $columns['row'] = $query->params->get($table->getIdentityColumn());
        }

        $revisions = $this->_table->select($columns);
        return $revisions;
    }

    /**
     * Count revisions
     *
     * @param  string  $status  The row status
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
     * Delete one or all revisions
     *
     * @param  string  $status  The row status
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

    /**
     * Insert a new revision
     *
     * @param  string  $status  The row status
     * @return void
     */
    protected function _insertRevision()
    {
    	$table = $this->getTable();

    	// Get the row data
    	if ($this->getStatus() == Library\Database::STATUS_UPDATED) {
            $data = $this->getData(true);
        } else {
            $data = $this->getData();
        }

        //Get the row status
        $status = $this->getStatus();
        if ($status == Library\Database::STATUS_LOADED) {
            $status = Library\Database::STATUS_CREATED;
        }

    	// Create the new revision
    	$revision = $this->_table->getRow();
    	$revision->table    = $table->getBase();
        $revision->row      = $this->id;
        $revision->status   = $status;
        $revision->data     = (object) $table->filter($data);

    	// Set the created_on and created_by information based on the creatable or modifiable data in the row
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
    	if ($status == Library\Database::STATUS_UPDATED || $status == Library\Database::STATUS_DELETED)
    	{
    	    $query = $this->getObject('lib:database.query.select')
        	    ->where('table = :table')
        	    ->where('row = :row')
        	    ->order('revision', 'DESC')
        	    ->bind(array(
        	    	'table' => $table->getBase(),
        	        'row'   => $this->id
        	    ));
        	
        	$revision->revision = $this->_table->select($query, Library\Database::FETCH_ROW)->revision + 1;
    	}

        // Store the revision
        return $revision->save();
    }
}