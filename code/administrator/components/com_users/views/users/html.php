<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Users HTML View Class
 *
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersViewUsersHtml extends ComDefaultViewHtml
{
	public function display()
	{
		$this->getToolbar()
			->append('divider')
			->append('enable')
			->append('disable');

		return parent::display();
	}
}