<?php
/**
* @version		$Id: radio.php 14401 2010-01-26 14:10:00Z louis $
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

use Kodekit\Library;

/**
 * Renders a radio element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 */

class JElementRadio extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Radio';

	function fetchElement($name, $value, $param, $group)
	{
		$options = array ();
		foreach ($param->children() as $option)
		{
			$val	= (string) $option->attributes()->value;
			$text	= (string) $option->attributes()->data;
			$options[] = (object) array('id' => $val, 'value' => $val, 'label' => $text);
		}

        $config = array(
            'options'     => (object) $options,
            'name'     => $group.'['.$name.']',
            'selected' => $value,
        );

        $template = Library\ObjectManager::getInstance()->getObject('com:pages.view.page')->getTemplate();
        return Library\ObjectManager::getInstance()->getObject('lib:template.helper.select', array('template' => $template))->radiolist($config);
	}
}
