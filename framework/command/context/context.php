<?php
/**
 * @package     Koowa_Command
 * @subpackage  Context
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Command Context
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Command
 * @subpackage  Context
 */
class CommandContext extends Config implements CommandContextInterface
{
    /**
     * The command subject
     *
     * @var  object
     */
    protected $_subject;

    /**
    * Get the command subject 
    *     
    * @return object	The command subject
    */
    public function getSubject()
    {
        return $this->_subject;
    }
    
    /**
    * Set the command subject
    *
    * @param ServiceInterface $subject The command subject
    * @return CommandContext
    */
    public function setSubject(ServiceInterface $subject)
    {
        $this->_subject = $subject;
        return $this;
    }
}
