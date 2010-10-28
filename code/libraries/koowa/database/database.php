<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Database Namespace class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 */
class KDatabase
{
	/**
	 * Database operations
	 */
	const OPERATION_SELECT = 1;
	const OPERATION_INSERT = 2;
	const OPERATION_UPDATE = 4;
	const OPERATION_DELETE = 8;
	const OPERATION_SHOW   = 16;

	/**
	 * Database result mode
	 */
	const RESULT_STORE = 0;
	const RESULT_USE   = 1;
	
	/**
	 * Database fetch mode
	 */
	const FETCH_ROW         = 0;
	const FETCH_ROWSET      = 1;

	const FETCH_ARRAY       = 0;
	const FETCH_ARRAY_LIST  = 1;
	const FETCH_FIELD       = 2;
	const FETCH_FIELD_LIST  = 3;
	const FETCH_OBJECT      = 4;
	const FETCH_OBJECT_LIST = 5;
	
	/**
	 * Row states
	 */
	const STATUS_LOADED   = 'loaded';
	const STATUS_DELETED  = 'deleted';
    const STATUS_INSERTED = 'inserted';
    const STATUS_UPDATED  = 'updated';
    const STATUS_FAILED   = 'failed';
	
}