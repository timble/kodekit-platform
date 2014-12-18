<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database Model
 *
 * Provides interaction with a database
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Model
 */
class ModelDatabase extends ModelAbstract
{
    /**
     * Table object or identifier
     *
     * @var string|object
     */
    protected $_table;

    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_table = $config->table;

        //Calculate the aliases based on the location of the table
        $model = $database = $this->getTable()->getIdentifier()->toArray();

        //Create database.rowset -> model.entity alias
        $database['path'] = array('database', 'rowset');
        $model['path']    = array('model'   , 'entity');

        $this->getObject('manager')->registerAlias($model, $database);

        //Create database.row -> model.entity alias
        $database['path'] = array('database', 'row');
        $database['name'] = StringInflector::singularize($database['name']);

        $model['path'] = array('model', 'entity');
        $model['name'] = StringInflector::singularize($model['name']);

        $this->getObject('manager')->registerAlias($model, $database);

        //Behavior depends on the database. Need to add if after database has been set.
        $this->addBehavior('indexable');
    }

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'table'      => $this->getIdentifier()->name,
            'behaviors'  => array('paginatable', 'sortable'),
        ));

        parent::_initialize($config);
    }

    /**
     * Create a new entity for the data source
     *
     * @param ModelContext $context A model context object
     * @return  ModelEntityInterface The entity
     */
    protected function _actionCreate(ModelContext $context)
    {
        //Get the data
        $data = ModelContext::unbox($context->entity);

        //Entity options
        $options = array(
            'data'            => $data,
            'identity_column' => $context->getIdentityKey()
        );

        if(!is_numeric(key($data))) {
            $entity = $this->getTable()->createRow($options);
        } else {
            $entity = $this->getTable()->createRowset($options);
        }

        return $entity;
    }

    /**
     * Fetch a new entity from the data source
     *
     * @param ModelContext $context A model context object
     * @return ModelEntityInterface The entity
     */
    protected function _actionFetch(ModelContext $context)
    {
        $state   = $context->state;
        $table   = $this->getTable();

        //Entity options
        $options = array(
            'identity_column' => $context->getIdentityKey()
        );

        //Select the rows
        if (!$state->isEmpty())
        {
            $context->query->columns('tbl.*');
            $context->query->table(array('tbl' => $table->getName()));

            $this->_buildQueryColumns($context->query);
            $this->_buildQueryJoins($context->query);
            $this->_buildQueryWhere($context->query);
            $this->_buildQueryGroup($context->query);

            $data = $table->select($context->query, Database::FETCH_ROWSET, $options);
        }
        else $data = $table->createRowset($options);

        return $data;
    }

    /**
     * Get the total number of entities
     *
     * @param ModelContext $context A model context object

     * @return string  The output of the view
     */
    protected function _actionCount(ModelContext $context)
    {
        $context->query->columns('COUNT(*)');
        $context->query->table(array('tbl' => $this->getTable()->getName()));

        $this->_buildQueryJoins($context->query);
        $this->_buildQueryWhere($context->query);

        return $this->getTable()->count($context->query);
    }

    /**
     * Method to get a table object
     *
     * @return DatabaseTableInterface
     */
    public function getTable()
    {
        if(!($this->_table instanceof DatabaseTableInterface))
		{
            //Make sure we have a table identifier
            if(!($this->_table instanceof ObjectIdentifier)) {
                $this->setTable($this->_table);
            }

            $this->_table = $this->getObject($this->_table);
        }

        return $this->_table;
    }

    /**
     * Method to set a table object attached to the model
     *
     * @param	mixed	$table An object that implements ObjectInterface, ObjectIdentifier object
	 * 					       or valid identifier string
     * @throws  \UnexpectedValueException   If the identifier is not a table identifier
     * @return  ModelDatabase
     */
    public function setTable($table)
	{
		if(!($table instanceof DatabaseTableInterface))
		{
			if(is_string($table) && strpos($table, '.') === false )
		    {
		        $identifier         = $this->getIdentifier()->toArray();
		        $identifier['path'] = array('database', 'table');
		        $identifier['name'] = StringInflector::pluralize(StringInflector::underscore($table));

                $identifier = $this->getIdentifier($identifier);
		    }
		    else  $identifier = $this->getIdentifier($table);

			if($identifier->path[1] != 'table') {
				throw new \UnexpectedValueException('Identifier: '.$identifier.' is not a table identifier');
			}

			$table = $identifier;
		}

		$this->_table = $table;

		return $this;
	}

    /**
     * Get the model context
     *
     * @return  ModelContext
     */
    public function getContext()
    {
        $context        = parent::getContext();
        $context->query = $this->getObject('lib:database.query.select');

        return $context;
    }

    /**
     * Builds SELECT columns list for the query
     *
     * @param DatabaseQueryInterface $query
     */
    protected function _buildQueryColumns(DatabaseQuerySelect $query)
    {

    }

    /**
     * Builds JOINS clauses for the query
     *
     * @param DatabaseQueryInterface $query
     */
    protected function _buildQueryJoins(DatabaseQuerySelect $query)
    {

    }

    /**
     * Builds WHERE clause for the query
     *
     * @param DatabaseQueryInterface $query
     */
    protected function _buildQueryWhere(DatabaseQuerySelect $query)
    {

    }

    /**
     * Builds GROUP BY clause for the query
     *
     * @param DatabaseQueryInterface $query
     */
    protected function _buildQueryGroup(DatabaseQuerySelect $query)
    {

    }
}