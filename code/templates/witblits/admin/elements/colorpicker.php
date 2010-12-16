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
 * Renders a text element
 *
 * @package 	Joomla.Framework
 * @subpackage	Parameter
 * @since		1.5
 */

class JElementColorpicker extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Colorpicker';
	
	function fetchTooltip($label, $description, &$node, $control_name='', $name='')
	{
		$output = '<span class="palette"><label class="to-label tooltip" title="'.JText::_( $description ).'" id="'.$control_name.$name.'-lbl" for="'.$control_name.$name.'">'.JText::_( $label ).'</label>';		
		return $output;
	}
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$size = ( $node->attributes('size') ? 'size="'.$node->attributes('size').'"' : '' );
		//$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : '' );
        $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);

		return '<span class="picker-wrap"><input type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" class="form-colorpicker text-input" '.$size.' /><span class="color-preview" style="background-color:'.$value.';" id="preview_'.$name.'">&nbsp;</span></span><a href="#" class="picker" id="picker_'.$name.'"><img src="' . JURI::root() . 'templates/witblits/admin/images/swatch.png" /></a></span>';
	}
}