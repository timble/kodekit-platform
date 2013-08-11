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
 * Languages Element
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class JElementLanguages extends JElement
{
	var	$_name = 'Languages';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$user =  Library\ObjectManager::getInstance()->getObject('user');

		if(!($user->getRole() >= 23) && $node->attributes('client') == 'administrator') {
			return JText::_('No Access');
		}

        return  Library\ObjectManager::getInstance()->getObject('com:users.template.helper.listbox')->languages(array(
            'selected'    => $value,
            'application' => $node->attributes('client'),
            'name'        => $control_name . '[' . $name . ']'));
    }
}
