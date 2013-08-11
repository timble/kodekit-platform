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
 * Group Html View
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Component\Users
 */
class UsersViewGroupHtml extends Library\ViewHtml
{
    public function render()
    {
        $group = $this->getModel()->getRow();

        $this->users = $this->getObject('com:users.model.groups_users')->group_id($group->id)->getRowset()->user_id;
        
        return parent::render();
    }
}