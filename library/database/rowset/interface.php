<?php
/**
 * @package     Koowa_Database
 * @subpackage  Rowset
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Database Rowset Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Rowset
 */
interface DatabaseRowsetInterface extends DatabaseRowInterface
{
	/**
     * Add rows to the rowset
     *
     * @param  array   $rows    An associative array of row data to be inserted.
     * @param  string  $status  The row(s) status
     * @return DatabaseRowsetInterface
     * @see __construct
     */
    public function addRow(array $rows, $status = null);

	/**
     * Find a row in the rowset based on a needle
     *
     * This functions accepts either a know position or associative array of key/value pairs
     *
     * @param 	string $needle The position or the key to search for
     * @return DatabaseRowInterface
     */
    public function find($needle);
}