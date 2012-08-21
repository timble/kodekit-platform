<?php
/**
* @version      $Id$
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
interface KControllerToolbarInterface
{ 
	/**
     * Get the controller object
     * 
     * @return  KController
     */
    public function getController();

    /**
     * Get the toolbar's name
     *
     * @return string
     */
    public function getName();

    /**
     * Add a separator
     *
     * @return  KControllerToolbarInterface
     */
    public function addSeparator();
     
    /**
     * Add a command
     *
     * @param   string	The command name
     * @param	mixed	Parameters to be passed to the command
     * @return  KControllerToolbarInterface
     */
    public function addCommand($name, $config = array());
    
 	/**
     * Get the list of commands
     *
     * @return  array
     */
    public function getCommands();
 
    /**
     * Reset the commands array
     *
     * @return  KConttrollerToolbarInterface
     */
    public function reset();
}