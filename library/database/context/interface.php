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
 * Database Context Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
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
    public function getAffected($affected);

    /**
     * Get the number of affected rows
     *
     * @param integer $affected
     * @return DatabaseContext
     */
    public function setAffected($affected);
}