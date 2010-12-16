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

class JElementLoader extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Loader';	
	
	function fetchTooltip($label, $description, &$node, $control_name, $name) 
	{
		return '&nbsp;';
	}

	function fetchElement($name, $value, &$node, $control_name) 
	{
		$document = Jfactory::getDocument();
		$header_media = $document->addScript(JURI::root() . 'templates/witblits/admin/mooRainbow.js');
		$header_media .= $document->addScript(JURI::root() . 'templates/witblits/admin/admin.js'); 
		$header_media .= $document->addScript(JURI::root() . 'templates/witblits/admin/toggler.js'); 
		$header_media .= $document->addStyleSheet(JURI::root() . 'templates/witblits/admin/admin.css');
		return $header_media;
	}
}