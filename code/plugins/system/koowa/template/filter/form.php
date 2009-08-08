<?php
/**
* @version      $Id$
* @category		Koowa
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPL <http://www.gnu.org/licenses/gpl.html>
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