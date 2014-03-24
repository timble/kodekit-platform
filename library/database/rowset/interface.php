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
 * Database Rowset Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
interface DatabaseRowsetInterface extends DatabaseRowInterface
{
    /**
     * Find a row in the rowset based on a needle
     *
     * This functions accepts either a know position or associative array of key/value pairs
     *
     * @param 	string $needle The position or the key to search for
     * @return DatabaseRowInterface
     */
    public function find($needle);

    /**
     * Add rows to the rowset
     *
     * @param  array   $rows    An associative array of row data to be inserted.
     * @param  string  $status  The row(s) status
     * @return DatabaseRowsetInterface
     * @see __construct
     */
    public function addRow(array $rows, $status = null);
}