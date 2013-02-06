<?php
/**
 * @version     $Id$
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Database Query Interface
 *
 * @author      Gergo Erdosi <gergo@timble.net>
 * @package     Koowa_Database
 * @subpackage  Query
 */
interface KDatabaseQueryInterface
{
    /**
     * Bind values to a corresponding named placeholders in the query.
     *
     * @param  array $params Associative array of parameters.
     * @return \KDatabaseQueryInterface
     */
    public function bind(array $params);

    /**
     * Get the query parameters
     *
     * @return KObjectArray
     */
    public function getParams();

    /**
     * Set the query parameters
     *
     * @param KObjectArray $params  The query parameters
     * @return \KDatabaseQueryInterface
     */
    public function setParams(KObjectArray $params);

    /**
     * Gets the database adapter
     *
     * @return \KDatabaseAdapterInterface
     */
    public function getAdapter();
    
    /**
     * Set the database adapter
     *
     * @param  \KDatabaseAdapterInterface $adapter A KDatabaseAdapterInterface object
     * @return \KDatabaseQueryInterface
     */
    public function setAdapter(KDatabaseAdapterInterface $adapter);
}