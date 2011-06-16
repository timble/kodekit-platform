<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link        http://www.nooku.org
*/

/**
 * Controller Toolbar Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 */
interface KControllerToolbarInterface
{   
    /**
     * Get the toolbar's name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Append a command
     * 
     * @param   string	The command name
     * @param	mixed	Parameters to be passed to the command
     * @return  KToolbarInterface
     */
    public function append($command, $config = array());
    
    /**
     * Prepend a command
     *
     * @param   string	The command name
     * @param	mixed	Parameters to be passed to the command
     * @return  KToolbarInterface
     */
    public function prepend($command, $config = array());
}