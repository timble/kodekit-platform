<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Users View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class UsersViewUsersHtml extends Library\ViewHtml
{
	public function render()
	{
	    $this->groups       = $this->getObject('com:users.model.groups')->getRowset();
		$this->roles        = $this->getObject('com:users.model.roles')->getRowset();
		$this->groups_users = $this->getObject('com:users.model.groups_users')->set('type', 'custom')->getRowset();

		return parent::render();
	}
}