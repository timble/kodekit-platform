<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @subpackage	Button
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

/**
 * Export to CSV button for a toolbar
 * 
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 * @subpackage	Button
 */
class KToolbarButtonCsv extends KToolbarButtonAbstract
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		if(!isset($config->icon)) {
			$config->icon = 'icon-32-export';
		}
		
		parent::__construct($config);
		
		KFactory::get('lib.joomla.document')->addStyleDeclaration('.icon-32-export { background-image: url('.KRequest::root().'/media/lib_koowa/images/32/export.png); }');		
	}
	
	public function getLink()
	{
		// Unset limit and offset
		$url = clone KRequest::url();
		$query = parse_str($url->getQuery(), $vars);
		unset($vars['limit']);
		unset($vars['offset']);
		$vars['format'] = 'csv';
		$url->setQuery(http_build_query($vars));
		
		return (string) $url;
	}
}