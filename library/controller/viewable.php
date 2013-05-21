<?php
/**
 * @package		Nooku_Controller
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Controller Viewable Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Nooku_Controller
 */
interface ControllerViewable
{
    /**
     * Get the controller view
     *
     * @throws	\UnexpectedValueException	If the view doesn't implement the ViewInterface
     * @return	ViewInterface
     */
    public function getView();

    /**
     * Set the controller view
     *
     * @param	mixed	$view   An object that implements ObjectInterface, ObjectIdentifier object
     * 					        or valid identifier string
     * @return	ControllerInterface
     */
    public function setView($view);
}