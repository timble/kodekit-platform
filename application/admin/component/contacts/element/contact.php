<?php
/**
 * @package        Nooku_Server
 * @subpackage     Contacts
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Contact Element Class
 *
 * @author     Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package    Nooku_Server
 * @subpackage Contacts
 */

class JElementContact extends JElement
{
	var	$_name = 'Contact';

	function fetchElement($name, $value, &$node, $control_name)
	{
        $config = array(
            'name'     => $control_name . '[' . $name . ']',
            'selected' => $value,
            'table'    => $node->attributes('table'),
            'attribs'  => array('class' => 'inputbox'),
            'autocomplete' => true,
        );

        $template = Library\ObjectManager::getInstance()->getObject('com:contacts.controller.contact')->getView()->getTemplate();
        $html     = Library\ObjectManager::getInstance()->getObject('com:contacts.template.helper.listbox', array('template' => $template))->contacts($config);

        return $html;
	}
}
