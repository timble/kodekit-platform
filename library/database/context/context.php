<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database Context
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseContext extends Command implements DatabaseContextInterface
{
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