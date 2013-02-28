<?php
/**
* @package		Koowa_Controller
* @subpackage 	Toolbar
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Controller Toolbar Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage 	Toolbar
 */
interface KControllerToolbarInterface extends \IteratorAggregate
{
    /**
     * Get the toolbar's name
     *
     * @return string
     */
    public function getName();

    /**
     * Add a command by name
     *
     * @param   string	The command name
     * @param	mixed	Parameters to be passed to the command
     * @return  \KControllerToolbarCommand  The command object that was added
     */
    public function addCommand($name, $config = array());

    /**
     * Get a command by name
     *
     * @param string $name  The command name
     * @param array $config An optional associative array of configuration settings
     * @return mixed KControllerToolbarCommand if found, false otherwise.
     */
    public function getCommand($name, $config = array()) ;
    
 	/**
     * Get the list of commands
     *
     * @return  array
     */
    public function getCommands();
}