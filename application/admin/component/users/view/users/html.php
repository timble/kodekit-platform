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
 * Users Html View
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Users
 */
class UsersViewUsersHtml extends Library\ViewHtml
{
	public function render()
	{
	    $this->groups       = $this->getObject('com:users.model.groups')->fetch();
		$this->roles        = $this->getObject('com:users.model.roles')->fetch();
		$this->groups_users = $this->getObject('com:users.model.groups_users')->fetch();

		return parent::render();
	}
}