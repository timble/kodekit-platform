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
 * Database Namespace
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
class Database
{
	/**
	 * Database result mode
	 */
	const RESULT_STORE = 0;
	const RESULT_USE   = 1;

    const MULTI_QUERY = 2;

    /**
	 * Database fetch mode
	 */

	const FETCH_ARRAY       = 0;
	const FETCH_ARRAY_LIST  = 1;
	const FETCH_FIELD       = 2;
	const FETCH_FIELD_LIST  = 3;
	const FETCH_OBJECT      = 4;
	const FETCH_OBJECT_LIST = 5;

	const FETCH_ROW         = 6;
	const FETCH_ROWSET      = 7;

	/**
	 * Row states
	 */
	const STATUS_MODIFIED = 'modified';
	const STATUS_FETCHED  = 'fetched';
	const STATUS_DELETED  = 'deleted';
    const STATUS_CREATED  = 'created';
    const STATUS_UPDATED  = 'updated';
    const STATUS_FAILED   = 'failed';
}