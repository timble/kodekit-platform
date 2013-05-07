<?php
/**
 * @package     Koowa_Command
 * @subpackage  Context
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Command Context Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Command
 */
interface CommandContextInterface
{
    /**
    * Get the command subject 
    *     
    * @return ObjectInterface The command subject
    */
    public function getSubject();

    /**
     * Set the command subject
     *
     * @param ObjectInterface $subject The command subject
     * @return CommandContextInterface
     */
    public function setSubject(ObjectInterface $subject);
}
