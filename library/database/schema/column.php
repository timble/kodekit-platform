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
 * Column Database Schema
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseSchemaColumn extends Object
{
	/**
	 * Column name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Column type
	 *
	 * @var	string
	 */
	public $type;

	/**
	 * Column length
	 *
	 * @var integer
	 */
	public $length;

	/**
	 * Column scope
	 *
	 * @var string
	 */
	public $scope;

	/**
	 * Column default value
	 *
	 * @var string
	 */
	public $default;

	/**
	 * Required column
	 *
	 * @var bool
	 */
	public $required = false;

	/**
	 * Is the column a primary key
	 *
	 * @var bool
	 */
	public $primary = false;

	/**
	 * Is the column auto-incremented
	 *
	 * @var	bool
	 */
	public $autoinc = false;

	/**
	 * Is the column unique
	 *
	 * @var	bool
	 */
	public $unique = false;

	/**
	 * Related index columns
	 *
	 * @var	bool
	 */
	public $related = array();

	/**
	 * Filter object
	 *
	 * Public access is allowed via __get() with $filter.
	 *
	 * @var	FilterInterface
	 */
	protected $_filter;

	/**
     * Implements the virtual $filter property.
     *
     * The value can be a Filter object, a filter name, an array of filter
     * names or a filter identifier
     *
     * @param 	string 	$key   The virtual property to set, only accepts 'filter'
     * @param 	string 	$value Set the virtual property to this value.
     */
    public function __set($key, $value)
    {
        if ($key == 'filter') {
        	$this->_filter = $value;
        }
    }

    /**
     * Implements access to $_filter by reference so that it appears to be a public $filter property.
     *
     * @param   string  $key The virtual property to return, only accepts 'filter'
     * @return  mixed   The value of the virtual property.
     */
    public function __get($key)
    {
        if ($key == 'filter')
        {
           if(!isset($this->_filter)) {
                $this->_filter = $this->type;
            }

            if(!($this->_filter instanceof FilterInterface)) {
                $this->_filter = $this->getObject('lib:filter.factory')->getFilter($this->_filter);
            }

            return $this->_filter;
        }
    }
}