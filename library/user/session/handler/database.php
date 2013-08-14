<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database User Session Handler
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\User
 */
class UserSessionHandlerDatabase extends UserSessionHandlerAbstract
{
    /**
     * Table object or identifier
     *
     * @var string|object
     */
    protected $_table = null;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return UserSessionHandlerDatabase
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (is_null($config->table)) {
            throw new \InvalidArgumentException('table option is required');
        }

        $this->_table = $config->table;
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'table' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Read session data for a particular session identifier from the session handler backend
     *
     * @param   string  $session_id  The session identifier
     * @return  string  The session data
     */
    public function read($session_id)
    {
        $result = '';

        if ($this->getTable()->isConnected())
        {
            $row = $this->_table->select($session_id, Database::FETCH_ROW);

            if (!$row->isNew()) {
                $result = $row->data;
            }
        }

        return $result;
    }

    /**
     * Write session data to the session handler backend
     *
     * @param   string  $session_id    The session identifier
     * @param   string  $session_data  The session data
     * @return  boolean  True on success, false otherwise
     */
    public function write($session_id, $session_data)
    {
        $result = false;

        if ($this->getTable()->isConnected())
        {
            $row = $this->_table->select($session_id, Database::FETCH_ROW);

            if ($row->isNew()) {
                $row->id   = $session_id;
            }

            $row->time = time();
            $row->data = $session_data;

            $result = $row->save();
        }

        return $result;
    }

    /**
     * Destroy the data for a particular session identifier in the session handler backend
     *
     * @param   string  $session_id  The session identifier
     * @return  boolean  True on success, false otherwise
     */
    public function destroy($session_id)
    {
        $result = false;

        if ($this->getTable()->isConnected())
        {
            $row = $this->_table->select($session_id, Database::FETCH_ROW);

            if (!$row->isNew()) {
                $result = $row->delete();
            }
        }

        return $result;
    }

    /**
     * Garbage collect stale sessions from the SessionHandler backend.
     *
     * @param   integer  $maxlifetime  The maximum age of a session
     * @return  boolean  True on success, false otherwise
     */
    public function gc($maxlifetime)
    {
        $result = false;

        if ($this->getTable()->isConnected())
        {
            $query = $this->getObject('lib:database.query.select')
                ->where('time < :time')
                ->bind(array('time' => (int)(time() - $maxlifetime)));

            $result = $this->_table->select($query, Database::FETCH_ROWSET)->delete();
        }

        return $result;
    }

    /**
     * Get a table object, create it if it does not exist.
     *
     * @throws UnexpectedValueException  If the table object doesn't implement DatabaseTableInterface
     * @return DatabaseTableInterface
     */
    public function getTable()
    {
        if (!($this->_table instanceof DatabaseTableInterface))
        {
            //Make sure we have a table identifier
            if (!($this->_table instanceof ObjectIdentifier)) {
                $this->setTable($this->_table);
            }

            $this->_table = $this->getObject($this->_table);

            if (!($this->_table instanceof DatabaseTableInterface))
            {
                throw new \UnexpectedValueException(
                    'Table: ' . get_class($this->_table) . ' doed not implement DatabaseTableInterface'
                );
            }
        }

        return $this->_table;
    }

    /**
     * Set a table object attached to the handler
     *
     * @param   mixed   $table An object that implements ObjectInterface, ObjectIdentifier object
     *                         or valid identifier string
     * @return UserSessionHandlerDatabase
     */
    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }
}