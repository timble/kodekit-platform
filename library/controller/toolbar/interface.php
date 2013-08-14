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
 * Controller Toolbar Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
interface ControllerToolbarInterface extends \IteratorAggregate
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
     * @return  ControllerToolbarCommand  The command object that was added
     */
    public function addCommand($name, $config = array());

    /**
     * Get a command by name
     *
     * @param string $name  The command name
     * @param array $config An optional associative array of configuration settings
     * @return mixed ControllerToolbarCommand if found, false otherwise.
     */
    public function getCommand($name, $config = array()) ;
    
 	/**
     * Get the list of commands
     *
     * @return  array
     */
    public function getCommands();
}