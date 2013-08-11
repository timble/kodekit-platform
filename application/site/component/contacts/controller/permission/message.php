<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Message Controller Permission
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Contacts
 */
class ContactsControllerPermissionMessage extends ContactsControllerPermissionContact
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