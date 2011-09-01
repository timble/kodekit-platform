<?php
/**
 * @version     $Id: sessions.php 862 2011-04-08 01:34:13Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

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
	public function display()
	{
		$this->assign('groups', KFactory::get('com://admin/groups.model.groups')->getList());

		return parent::display();
	}
}