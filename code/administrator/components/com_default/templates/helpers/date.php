<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Components
 * @subpackage	Default
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Helper Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Default
 * @uses   		KFactory
 */
class ComDefaultTemplateHelperDate extends KTemplateHelperDate
{
	/**
	 * Returns formated date according to current local and adds time offset
	 *
	 * @param	string	A date in ISO 8601 format or a unix time stamp
	 * @param	string	format optional format for strftime
	 * @returns	string	formated date
	 * @see		strftime
	 */
	public function format($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'format' => JText::_('DATE_FORMAT_LC1'),
			'gmt_offset' => KFactory::get('lib.joomla.config')->getValue('config.offset')
 		));
 		
		return parent::format($config);
	}
}
