<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Modal Helper Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */
class ComFilesTemplateHelperModal extends KTemplateHelperAbstract
{
	public function select($config = array()) 
	{
		$config = new KConfig($config);
		$config->append(array(
			'name' => '',
			'visible' => true,
			'link' => '',
			'link_text' => JText::_('Select')
		))->append(array(
			'value' => $config->name
		));
		
		$input = '<input name="%1$s" id="%1$s" value="%2$s" %3$s size="40" />';
		
		$link = '<a class="modal-button"
					rel="{\'ajaxOptions\': {\'method\': \'get\'}, \'handler\': \'iframe\', \'size\': {\'x\': 700}}"
					href="%s">'
				.$config->link_text
				.'</a>';
				
		$html = sprintf($input, $config->name, $config->value, $config->visible ? 'type="text" readonly' : 'type="hidden"');
		$html .= sprintf($link, $config->link);
		
		return $html;
	}
}