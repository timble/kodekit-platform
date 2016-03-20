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
 * Database Context Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
interface DatabaseContextInterface extends CommandInterface
{
    /**
     * Get the query object
     *
     * @return DatabaseQueryInterface|string
     */
    public function getQuery();

    /**
     * Set the query object
     *
     * @param DatabaseQueryInterface|string $query
     * @return $this
     */
    public function setQuery($query);

    /**
     * Get the number of affected rows
     *
     * @return integer
     */
    public function getAffected();

    /**
     * Get the number of affected rows
     *
     * @param integer $affected
     * @return DatabaseContext
     */
    public function setAffected($affected);
}