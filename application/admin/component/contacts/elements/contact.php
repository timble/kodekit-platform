<?php
/**
 * @package        Nooku_Server
 * @subpackage     Contacts
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

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

        $template = KServiceManager::get('com://admin/contacts.controller.contact')->getView()->getTemplate();
        $html     = KServiceManager::get('com://admin/contacts.template.helper.listbox', array('template' => $template))->contacts($config);

        return $html;


		$db = JFactory::getDBO();

		$query = 'SELECT a.id, CONCAT( a.name, " - ",a.con_position ) AS text, a.categories_category_id '
		. ' FROM #__contacts AS a'
		. ' INNER JOIN #__categories AS c ON a.categories_category_id = c.id'
		. ' WHERE a.published = 1'
		. ' AND c.published = 1'
		. ' ORDER BY a.categories_category_id, a.name'
		;
		$db->setQuery( $query );
		$options = $db->loadObjectList( );

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'text', $value, $control_name.$name );
	}
}
