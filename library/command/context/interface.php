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
 * Command Context Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
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
