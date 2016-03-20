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
 * Database Rowset Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Database
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
     * Insert a new row
     *
     * This function will either clone the row prototype, or create a new instance of the row object for each row
     * being inserted. By default the prototype will be cloned. The row will be stored by it's identity_column if
     * set or otherwise by it's object handle.
     *
     * @param   DatabaseRowInterface|array $row  A DatabaseRowInterface object or an array of row properties
     * @param   string  $status     The row status
     * @return  DatabaseRowsetInterface
     */
    public function insert($row, $status = null);

    /**
     * Removes a row from the rowset
     *
     * The row will be removed based on it's identity_column if set or otherwise by it's object handle.
     *
     * @param  DatabaseRowInterface $row
     * @throws \InvalidArgumentException if the object doesn't implement DatabaseRowInterface
     * @return DatabaseRowsetAbstract
     */
    public function remove($row);

    /**
     * Checks if the collection contains a specific row
     *
     * @param  DatabaseRowInterface $row
     * @throws \InvalidArgumentException if the object doesn't implement DatabaseRowInterface
     * @return  bool Returns TRUE if the object is in the set, FALSE otherwise
     */
    public function contains($row);
}