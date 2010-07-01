<?php
/**
* @version      $Id$
* @category		Koowa
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Template Write Filter Interface
 *
 * Processes the template on input
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter 
 */
interface KTemplateFilterRead
{
	/**
	 * Parse the text and filter it
	 *
	 * @param string Block of text to parse
	 * @return KTemplateFilterRead
	 */
	public function read(&$text);
}