<?php
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Message Controller Permission Class
 *
 * @author    	Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ContactsControllerPermissionMessage extends Library\ControllerPermissionAbstract
{
    public function canRender()
    {
        if($this->isDispatched()) {
            throw new Library\ControllerExceptionNotImplemented("Can't execute render method: render does not exist");
        }

        return true;
    }

    public function canAdd()
    {
        return true;
    }
}