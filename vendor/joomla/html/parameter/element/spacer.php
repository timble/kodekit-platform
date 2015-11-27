<?php
/**
* @version		$Id: spacer.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla.Framework
* @subpackage	Parameter
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 */

class JElementSpacer extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Spacer';

	function fetchTooltip($label, $description, $param, $group, $name)
    {
		return '&nbsp;';
	}

	function fetchElement($name, $value, $param, $group)
	{
		if ($value) {
			return $value;
		} else {
			return '<hr />';
		}
	}
}
