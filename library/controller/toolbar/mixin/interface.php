<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Toolbar Mixin Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
 */
interface ControllerToolbarMixinInterface
{
    /**
     * Add a toolbar
     *
     * @param   mixed $toolbar An object that implements ObjectInterface, ObjectIdentifier object
     *                         or valid identifier string
     * @param  array   $config   An optional associative array of configuration settings
     * @return  Object The mixer object
     */
    public function addToolbar($toolbar, $config = array());

    /**
     * Remove a toolbar
     *
     * @param   ControllerToolbarInterface $toolbar A toolbar instance
     * @return  Object The mixer object
     */
    public function removeToolbar(ControllerToolbarInterface $toolbar);

    /**
     * Check if a toolbar exists
     *
     * @param   string   $type The type of the toolbar
     * @return  boolean  TRUE if the toolbar exists, FALSE otherwise
     */
    public function hasToolbar($type);

    /**
     * Get a toolbar by type
     *
     * @param  string  $type   The toolbar type
     * @return ControllerToolbarInterface
     */
    public function getToolbar($type);

    /**
     * Gets the toolbars
     *
     * @return array  An associative array of toolbars, keys are the toolbar names
     */
    public function getToolbars();
}