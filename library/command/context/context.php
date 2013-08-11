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
 * Command Context
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
 */
class CommandContext extends ObjectConfig implements CommandContextInterface
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
    * @param ObjectInterface $subject The command subject
    * @return CommandContext
    */
    public function setSubject(ObjectInterface $subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * Set a command property
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function set($name, $value)
    {
        if (is_array($value)) {
            $this->_data[$name] = new ObjectConfig($value);
        } else {
            $this->_data[$name] = $value;
        }
    }
}
