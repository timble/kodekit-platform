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
 * Database Namespace
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Database
 */
class Database
{
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
	const STATUS_FETCHED  = 'fetched';
	const STATUS_DELETED  = 'deleted';
    const STATUS_CREATED  = 'created';
    const STATUS_UPDATED  = 'updated';
    const STATUS_FAILED   = 'failed';
}