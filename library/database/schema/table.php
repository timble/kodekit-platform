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
 * Table Database Schema
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseSchemaTable extends Object
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