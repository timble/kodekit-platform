<?php
/**
 * @version		$Id:helper.php 251 2008-06-14 10:06:53Z mjaz $
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Helper Class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @uses   		KFactory
 */
class KTemplateHelperDefault extends KObject
{
	/**
	 * Write a <script></script> element
	 *
	 * @access	public
	 * @param	string 	The name of the script file
	 * @param	string 	The relative or absolute path of the script file
	 * @param	boolean If true, the mootools library will be loaded
	 */
	public function script($filename, $path = 'media/plg_koowa/js/')
	{
		if(strpos($path, 'http') !== 0 && $path[0] != '/') {
			$path =  JURI::root(true).'/'.$path;
		};

		$document = KFactory::get('lib.joomla.document');
		$document->addScript( $path.$filename );
		return;
	}

	/**
	 * Write a <link rel="stylesheet" style="text/css" /> element
	 *
	 * @access	public
	 * @param	string 	The relative URL to use for the href attribute
	 */
	public function stylesheet($filename, $path = 'media/plg_koowa/css/', $attribs = array())
	{
		if(strpos($path, 'http') !== 0 && $path[0] != '/') {
			$path =  JURI::root(true).'/'.$path;
		};

		$document = KFactory::get('lib.joomla.document');
		$document->addStylesheet( $path.$filename, 'text/css', null, $attribs );
		return;
	}

	/**
	 * Returns formated date according to current local and adds time offset
	 *
	 * @access	public
	 * @param	string	date in an US English date format
	 * @param	string	format optional format for strftime
	 * @returns	string	formated date
	 * @see		strftime
	 */
	public function date($date, $format = null, $offset = NULL)
	{
		if ( ! $format ) {
			$format = JText::_('DATE_FORMAT_LC1');
		}

		if(is_null($offset))
		{
			$config = KFactory::get('lib.joomla.config');
			$offset = $config->getValue('config.offset');
		}

		$instance = KFactory::get('lib.joomla.date', array($date));
		$instance->setOffset($offset);

		return $instance->toFormat($format);
	}
}
