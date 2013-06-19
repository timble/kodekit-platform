<?php
/**
 * @package		Nooku_Controller
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Controller Modellable Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Nooku_Controller
 */
interface ControllerModellable
{
    /**
     * Get the controller model
     *
     * @throws	\UnexpectedValueException	If the model doesn't implement the ModelInterface
     * @return	ModelInterface
     */
    public function getModel();

    /**
     * Set the controller model
     *
     * @param	mixed	$model An object that implements ObjectInterface, ObjectIdentifier object
     * 					       or valid identifier string
     * @return	ControllerInterface
     */
    public function setModel($model);
}