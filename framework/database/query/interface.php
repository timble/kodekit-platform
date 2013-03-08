<?php
/**
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Database Query Interface
 *
 * @author      Gergo Erdosi <gergo@timble.net>
 * @package     Koowa_Database
 * @subpackage  Query
 */
interface DatabaseQueryInterface
{
    /**
     * Bind values to a corresponding named placeholders in the query.
     *
     * @param  array $params Associative array of parameters.
     * @return DatabaseQueryInterface
     */
    public function bind(array $params);

    /**
     * Get the query parameters
     *
     * @return ObjectArray
     */
    public function getParams();

    /**
     * Set the query parameters
     *
     * @param ObjectArray $params  The query parameters
     * @return DatabaseQueryInterface
     */
    public function setParams(ObjectArray $params);

    /**
     * Gets the database adapter
     *
     * @return DatabaseAdapterInterface
     */
    public function getAdapter();
    
    /**
     * Set the database adapter
     *
     * @param  DatabaseAdapterInterface $adapter A DatabaseAdapterInterface object
     * @return DatabaseQueryInterface
     */
    public function setAdapter(DatabaseAdapterInterface $adapter);
}