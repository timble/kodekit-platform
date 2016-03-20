<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Database Query Interface
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Library\Database
 */
interface DatabaseQueryInterface
{
    /**
     * Bind values to a corresponding named placeholders in the query.
     *
     * @param  array $parameters Associative array of parameters.
     * @return DatabaseQueryInterface
     */
    public function bind(array $parameters);

    /**
     * Get the query parameters
     *
     * @return  DatabaseQueryParameters
     */
    public function getParameters();

    /**
     * Set the query parameters
     *
     * @param array $parameters  The query parameters
     * @return DatabaseQueryInterface
     */
    public function setParameters(array $parameters);

    /**
     * Gets the database driver
     *
     * @return DatabaseDriverInterface
     */
    public function getDriver();

    /**
     * Set the database driver
     *
     * @param  DatabaseDriverInterface $driver A DatabaseDriverInterface object
     * @return DatabaseQueryInterface
     */
    public function setDriver(DatabaseDriverInterface $driver);

    /**
     * Render the query to a string.
     *
     * @return  string  The query string.
     */
    public function toString();
}