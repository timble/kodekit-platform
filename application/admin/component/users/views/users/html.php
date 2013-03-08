<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Users View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersViewUsersHtml extends ComDefaultViewHtml
{
	public function render()
	{
	    $this->groups       = $this->getService('com://admin/users.model.groups')->getRowset();
		$this->roles        = $this->getService('com://admin/users.model.roles')->getRowset();
		$this->groups_users = $this->getService('com://admin/users.model.groups_users')->set('type', 'custom')->getRowset();

		return parent::render();
	}
}