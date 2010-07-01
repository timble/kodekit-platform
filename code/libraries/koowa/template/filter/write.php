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
 * Process the template on output
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter 
 */
interface KTemplateFilterWrite
{
	/**
	 * Parse the text and filter it
	 *
	 * @param string Block of text to parse
	 * @return KTemplateFilterWrite
	 */
	public function write(&$text);
}