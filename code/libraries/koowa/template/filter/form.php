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
 * Template read filter to handle form html elements
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterForm extends KTemplateFilterAbstract implements KTemplateFilterRead
{
	/**
	 * Add unique token field
	 *
	 * @param string
	 * @return KTemplateFilterForm
	 */
	public function read(&$text)
	{
		// match all forms where method="post"
		$form 		= '<\s*form\s*';
		$anything 	= '.*';
		$quote		= '["\']';
		$method 	= '\s*method\s*=\s*'.$quote.'post'.$quote;
		$close		= '>';
		$pattern = '/('.$form.$anything.$method.$anything.$close.')/i';

		// add hidden token field to each match
		$replace = '\1'
			.PHP_EOL
			.'<input type="hidden" name="_token" value="'.JUtility::getToken().'" />';
		$text = preg_replace($pattern, $replace, $text);
		
		return $this;
	}
}