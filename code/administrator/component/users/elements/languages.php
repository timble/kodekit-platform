<?php
/**
 * @version     $Id: article.php 4368 2012-08-05 13:04:43Z gergoerdosi $
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Languages Element Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class JElementLanguages extends JElement
{
	var	$_name = 'Languages';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$user = KService::get('user');

		/*
		 * @TODO: change to acl_check method
		 */
		if(!($user->getRole() >= 23) && $node->attributes('client') == 'administrator') {
			return JText::_('No Access');
		}

		jimport('joomla.language.helper');

        return KService::get('com://admin/users.template.helper.listbox')->languages(array(
            'selected'    => $value,
            'application' => $node->attributes('client'),
            'name'        => $control_name . '[' . $name . ']'));
    }
}
