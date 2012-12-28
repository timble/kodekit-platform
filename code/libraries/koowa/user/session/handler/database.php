<?php
/**
 * @version     $Id$
 * @package     Koowa_User
 * @subpackage  Session
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Database User Session Handler Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Session
 */
class KUserSessionHandlerDatabase extends KUserSessionHandlerAbstract
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
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return KUserSessionHandlerDatabase
     */
    public function __construct(KConfig $config)
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
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
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

        if ($this->getTable()->getDatabase()->isConnected())
        {
            $row = $this->_table->select($session_id, KDatabase::FETCH_ROW);

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

        if ($this->getTable()->getDatabase()->isConnected())
        {
            $row = $this->_table->select($session_id, KDatabase::FETCH_ROW);

            if (!$row->isNew())
            {
                $row->time = time();
                $row->data = $session_data;

                $result = $row->save();
            }
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

        if ($this->getTable()->getDatabase()->isConnected())
        {
            $row = $this->_table->select($session_id, KDatabase::FETCH_ROW);

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

        if ($this->getTable()->getDatabase()->isConnected())
        {
            $query = $this->getService('koowa:database.query.select')
                ->where('time < :time')
                ->bind(array('time' => (int)(time() - $maxlifetime)));

            $result = $this->_table->select($query, KDatabase::FETCH_ROW)->delete();
        }

        return $result;
    }

    /**
     * Get a table object, create it if it does not exist.
     *
     * @throws UnexpectedValueException  If the table object doesn't implement KDatabaseTableInterface
     * @return KDatabaseTableInterface
     */
    public function getTable()
    {
        if (!($this->_table instanceof KDatabaseTableInterface))
        {
            //Make sure we have a table identifier
            if (!($this->_table instanceof KServiceIdentifier)) {
                $this->setTable($this->_table);
            }

            $this->_table = $this->getService($this->_table);

            if (!($this->_table instanceof KDatabaseTableInterface))
            {
                throw new \UnexpectedValueException(
                    'Table: ' . get_class($this->_table) . ' doed not implement KDatabaseTableInterface'
                );
            }
        }

        return $this->_table;
    }

    /**
     * Set a table object attached to the handler
     *
     * @param   mixed   $table An object that implements KObjectServiceable, KServiceIdentifier object
     *                         or valid identifier string
     * @return KUserSessionHandlerDatabase
     */
    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }
}