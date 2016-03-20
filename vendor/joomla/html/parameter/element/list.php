<?php
/**
* @version		$Id: list.php 14401 2010-01-26 14:10:00Z louis $
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
 * Renders a list element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 */

class JElementList extends JElement
{
	/**
	* Element type
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'List';

	function fetchElement($name, $value, $param, $group)
	{
        $template = Library\ObjectManager::getInstance()->getObject('com:pages.view.page')->getTemplate();

        $class  = $param->attributes()->class ? $param->attributes()->class : 'inputbox';
        $helper = Library\ObjectManager::getInstance()->getObject('lib:template.helper.select', array('template' => $template));

        $options = array ();
        foreach ($param->children() as $option)
        {
            $options[] =  $helper->option(array(
                'value' => (string) $option->attributes()->value,
                'text'  => (string) $option->attributes()->data,
            ));
        }

        $config = array(
            'options'  => $options,
            'name'     => $group.'['.$name.']',
            'selected' => $value,
            'attribs'  => array('class' => array($class))
        );

        return Library\ObjectManager::getInstance()->getObject('lib:template.helper.select', array('template' => $template))->optionlist($config);
	}
}
