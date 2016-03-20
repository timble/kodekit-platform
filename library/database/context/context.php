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
 * Database Context
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
class DatabaseContext extends Command implements DatabaseContextInterface
{
    /**
     * Constructor.
     *
     * @param  array|\Traversable  $attributes An associative array or a Traversable object instance
     */
    public function __construct($attributes = array())
    {
        ObjectConfig::__construct($attributes);
    }

    /**
     * Get the response object
     *
     * @return DatabaseQueryInterface|string
     */
    public function getQuery()
    {
        return ObjectConfig::get('query');
    }

    /**
     * Set the query object
     *
     * @param DatabaseQueryInterface|string $query
     * @return DatabaseContext
     */
    public function setQuery($query)
    {
        return ObjectConfig::set('query', $query);
    }

    /**
     * Get the number of affected rows
     *
     * @return integer
     */
    public function getAffected()
    {
        return ObjectConfig::get('affected');
    }

    /**
     * Get the number of affected rows
     *
     * @param integer $affected
     * @return DatabaseContext
     */
    public function setAffected($affected)
    {
        return ObjectConfig::set('affected', $affected);
    }
}