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
 * Table Database Schema
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
class DatabaseSchemaTable
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The storage engine
	 *
	 * @var string
	 */
	public $engine;

	/**
	 * Table type
	 *
	 * @var	string
	 */
	public $type;

	/**
	 * Table length
	 *
	 * @var integer
	 */
	public $length;

	/**
	 * Table next auto increment value
	 *
	 * @var integer
	 */
	public $autoinc;

	/**
	 * The tables character set and collation
	 *
	 * @var string
	 */
	public $collation;

	/**
	 * The tables description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * List of columns
	 *
	 * Associative array of columns, where key holds the columns name and the value is  an DatabaseSchemaColumn object.
	 *
	 * @var	array
	 */
	public $columns = array();

	/**
	 * List of indexes
	 *
	 * Associative array of indexes, where key holds the index name and the and the value is an object.
	 *
	 * @var	array
	 */
	public $indexes = array();
}