<?php
/**
 * @package     Koowa_Command
 * @subpackage  Context
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Command Context Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Command
 */
interface KCommandContextInterface
{
    /**
    * Get the command subject 
    *     
    * @return KObjectServiceable The command subject
    */
    public function getSubject();

    /**
     * Set the command subject
     *
     * @param KObjectServiceable $subject The command subject
     * @return KCommandContextInterface
     */
    public function setSubject(KObjectServiceable $subject);
}
