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
 * Model Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
interface ModelInterface
{
    /**
     * Reset the model data and state
     *
     * @param  boolean $default If TRUE use defaults when resetting the state. Default is TRUE
     * @return ModelAbstract
     */
    public function reset($default = true);

    /**
     * Set the model state values
     *
     * @param  array $values Set the state values
     * @return ModelAbstract
     */
    public function setState(array $values);

    /**
     * Method to get state object
     *
     * @return  ModelStateInterface  The model state object
     */
    public function getState();

    /**
     * State Change notifier
     *
     * This function is called when the state has changed.
     *
     * @param  string 	$name  The state name being changed
     * @return void
     */
    public function onStateChange($name);

    /**
     * Method to get a item
     *
     * @return  DatabaseRowInterface
     */
    public function getRow();

    /**
     * Get a list of items
     *
     * @return  DatabaseRowsetInterface
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
     * @return DatabaseRowsetInterface or DatabaseRowInterface
     */
    public function getData();

    /**
     * Get the model paginator object
     *
     * @return  ModelPaginator  The model paginator object
     */
    public function getPaginator();
}