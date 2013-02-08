<?php
/**
 * @version        $Id$
 * @package        Koowa_Model
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Model Interface
 *
 * @author        Johan Janssens <johan@nooku.org>
 * @package     Koowa_Model
 * @uses        KObject
 */
interface KModelInterface
{
    /**
     * Set the model state properties
     *
     * This function only acts on state properties it will reset (unsets) the $_rowset, $_row and $_total model
     * properties when a state changes.
     *
     * @param   string|array|object  $name  The name of the property, an associative array or an object
     * @param   mixed                $value The value of the property
     * @return  KModelAbstract
     */
    public function set($name, $value = null);

    /**
     * Get the model state properties
     *
     * If no state name is given then the function will return an associative array of all properties.
     *
     * If the property does not exist and a  default value is specified this is returned, otherwise the function return
     * NULL.
     *
     * @param   string  $name   The name of the property
     * @param   mixed   $default The default value
     * @return  mixed   The value of the property, an associative array or NULL
     */
    public function get($name = null, $default = null);

    /**
     * Reset all cached data and reset the model state to it's default
     *
     * @param   boolean If TRUE use defaults when resetting. Default is TRUE
     * @return  KModelInterface
     */
    public function reset($default = true);

    /**
     * Set the model state object
     *
     * @param KModelState $state A model state object
     * @return  KModelInterface
     */
    public function setState(KModelState $state);

    /**
     * Method to get state object
     *
     * @return  KModelState  The model state object
     */
    public function getState();

    /**
     * Method to get a item
     *
     * @return  KDatabaseRowInterface
     */
    public function getRow();

    /**
     * Get a list of items
     *
     * @return  KDatabaseRowsetInterface
     */
    public function getRowset();

    /**
     * Get the total amount of items
     *
     * @return  int
     */
    public function getTotal();

    /**
     * Get the model data
     *
     * If the model state is unique this function will call getRow(), otherwise it will call getRowset().
     *
     * @return KDatabaseRowsetInterface or KDatabaseRowInterface
     */
    public function getData();

    /**
     * Get the model paginator object
     *
     * @return  KModelPaginator  The model paginator object
     */
    public function getPaginator();
}