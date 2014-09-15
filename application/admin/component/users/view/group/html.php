<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Group Html View
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Component\Users
 */
class UsersViewGroupHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        $group = $this->getModel()->fetch();
        $users = $this->getObject('com:users.model.groups_users')->group_id($group->id)->fetch();

        foreach($users as $user) {
            $context->data->selected[] = $user->user_id;
        }
        
        parent::_fetchData($context);
    }
}