<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_View
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * List View Helper Class
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_View
 * @subpackage	Helper
 */
class KViewHelperList
{
	/**
	* Build the select list for access level
	*/
	public static function accesslevel( $row )
	{
		$db = KFactory::get('lib.joomla.database');

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__groups'
		. ' ORDER BY id'
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();
		$access = KViewHelper::_('select.genericlist',   $groups, 'access', 'class="inputbox" size="3"', 'value', 'text', intval( $row->access ), '', 1 );

		return $access;
	}

}