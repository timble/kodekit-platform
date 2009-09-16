<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Model
 * @subpackage	State
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * State interface
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Model
 * @subpackage	State
 */
interface KModelStateInterface
{
	/**
	 * Insert a state
	 *
	 * @param $name
	 * @param $filter
	 * @param $default
	 *
	 * @return KModelStateInterface
	 */
	public function insert($name, $filter, $default = null);

	/**
     * Remove an existing state
     *
     * @param   string		The name of the state
     * @return  KModelStateInterface
     */
    public function remove( $name );

	/**
     * Reset all cached data
     *
     * @return KModelStateInterface
     */
    public function reset();

	/**
     * Set the state data
     *
     * @param   array|object	An associative array of state data by name
     * @return  KModelStateInterface
     */
    public function setData(array $data);

    /**
     * Get the state data
     *
     * @return  array 	An associative array of state data by name
     */
    public function getData();

	/**
	 * Get a property
	 *
	 * @param   string	The name of the property
     * @param   mixed  	The default value
     * @return  mixed 	The value of the property
	 */
	public function get($property, $default = null);

 	/**
     * Set the object properties
     *
     * @param   string				The name of the property
     * @param   mixed  				The value of the property
     * @return  KModelStateInterface
     */
	public function set($property, $value);
}