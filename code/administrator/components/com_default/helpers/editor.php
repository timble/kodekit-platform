<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Default Paginator Helper
.*
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */

class ComDefaultHelperEditor extends KTemplateHelperAbstract
{
	/**
	 * Generates an HTML editor
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 */
	public function display($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'editor' 	=> null,
			'name'   	=> 'description',
			'row' 		=> null,
			'width' 	=> '100%',
			'height' 	=> '500',
			'cols' 		=> '75',
			'rows' 		=> '20',
			'buttons'	=> true,
			'options'	=> array()
		));

		$editor  = KFactory::get('lib.joomla.editor', array($config->editor));
		$options = KConfig::toData($config->options);

		return $editor->display($config->name, $config->row->{$config->name}, $config->width, $config->height, $config->cols, $config->rows, $config->buttons, $options);
	}
}