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
     * Test the connected status of the model.
     *
     * @return    boolean    Returns TRUE by default.
     */
    public function isConnected();

    /**
     * Reset all cached data and reset the model state to it's default
     *
     * @param   boolean If TRUE use defaults when resetting. Default is TRUE
     * @return  KModelInterface
     */
    public function reset($default = true);

    /**
     * Method to get state object
     *
     * @return  object  The state object
     */
    public function getState();

    /**
     * Method to get a item
     *
     * @return  KDatabaseRowInterface
     */
    public function getItem();

    /**
     * Get a list of items
     *
     * @return  KDatabaseRowsetInterface
     */
    public function getList();

    /**
     * Get the total amount of items
     *
     * @return  int
     */
    public function getTotal();

    /**
     * Get the model data
     *
     * If the model state is unique this function will call getItem(), otherwise it will call getList().
     *
     * @return KDatabaseRowsetInterface or KDatabaseRowInterface
     */
    public function getData();
}