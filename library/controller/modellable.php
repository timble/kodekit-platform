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
 * Controller Modellable Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
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