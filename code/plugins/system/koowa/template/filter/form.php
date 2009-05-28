<?php
/**
* @version      $Id$
* @category		Koowa
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Template rule to handle form html elements
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterForm extends KObject implements KTemplateFilterInterface
{
	/**
	 * Add unique token field 
	 *
	 * @param string $text
	 */
	public function parse(&$text) 
	{		 
		$contains_form 	= strpos($text, '</form>');
		$is_get			= preg_match('/method=[\'"]get[\'"]/', $text);
				 
        if( $contains_form && !$is_get) 
        {
        	$text = str_replace(
        		'</form>', 
        		'<input type="hidden" name="_token" value="'.JUtility::getToken().'" />'.PHP_EOL.'</form>', 
        		$text
        	);
        }
	}
}