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
 * Users Html View
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Users
 */
class UsersViewUsersHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
	{
	    $context->data->groups       = $this->getObject('com:users.model.groups')->fetch();
		$context->data->roles        = $this->getObject('com:users.model.roles')->fetch();
		$context->data->groups_users = $this->getObject('com:users.model.groups_users')->fetch();

        parent::_fetchData($context);
	}
}