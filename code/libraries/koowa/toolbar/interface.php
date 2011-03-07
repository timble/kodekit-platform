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
 * Toolbar interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 */
interface KToolbarInterface
{   
    /**
     * Render the toolbar
     *
     * @return  string  Html
     */
    public function render();
    
    /**
     * Get the toolbar's name
     *
     * @return string
     */
    public function getName();
    
    /**
     * Append a button
     *
     * @param   KToolbarButtonInterface|string  Button or identifier
     * @return  this
     */
    public function append($button);
    
    /**
     * Prepend a button
     *
     * @param   KToolbarButtonInterface|string  Button or identifier
     * @return  this
     */
    public function prepend($button);
}