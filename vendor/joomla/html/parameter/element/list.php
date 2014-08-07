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

use Nooku\Library;

/**
 * Renders a list element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
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

	function fetchElement($name, $value, &$node, $control_name)
	{
        $template = Library\ObjectManager::getInstance()->getObject('com:pages.view.page')->getTemplate();

        $class  = $node->attributes('class') ? $node->attributes('class') : 'inputbox';
        $helper = Nooku\Library\ObjectManager::getInstance()->getObject('lib:template.helper.select', array('template' => $template));

        $options = array ();
        foreach ($node->children() as $option)
        {
            $options[] =  $helper->option(array(
                'value' => $option->attributes('value'),
                'text'  => $option->data()
            ));
        }

        $config = array(
            'options'  => $options,
            'name'     => $control_name.'['.$name.']',
            'selected' => $value,
            'attribs'  => array('class' => array($class))
        );


        return Nooku\Library\ObjectManager::getInstance()->getObject('lib:template.helper.select', array('template' => $template))->optionlist($config);
	}
}
