<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
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

	function fetchTooltip($label, $description, &$node, $control_name, $name) 
	{
		return '&nbsp;';
	}

	function fetchElement($name, $value, &$node, $control_name)
	{
		if ($value) {
			return '<h2 class="heading">'.JText::_($value).'</h2>';
		} else {
			return '<span class="spacer">&nbsp;</span>';
		}
	}
}
