<?php
/**
* @version      $Id$
* @category		Koowa
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

/**
 * Template write filter to handle form html elements
 * 
 * For forms that use a post method this filter adds a token to prevent CSRF. For forms
 * that use a get method this filter adds the action url query params as hidden fields
 * to comply with the html form standard.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 * @see         http://www.w3.org/TR/html401/interact/forms.html#h-17.13.3.4
 */
class KTemplateFilterForm extends KTemplateFilterAbstract implements KTemplateFilterWrite
{
	/**
	 * Add unique token field
	 *
	 * @param string
	 * @return KTemplateFilterForm
	 */
	public function write(&$text)
	{	
		// POST : Add token 
		$text    = preg_replace('/(<form.*method="post".*>)/i', 
			'\1'.PHP_EOL.'<input type="hidden" name="_token" value="'.JUtility::getToken().'" />', 
			$text
		);
		
		// GET : Add query params
		$matches = array();
		if(preg_match_all('#<form.*action=".*\?(.*)".*method="get".*>#iU', $text, $matches))
		{
			foreach($matches[1] as $key => $query)
			{
				parse_str(str_replace('&amp;', '&', $query), $query);
				
				$input = '';
				foreach($query as $name => $value) {
					$input .= PHP_EOL.'<input type="hidden" name="'.$name.'" value="'.$value.'" />';
				}
				
				$text = str_replace($matches[0][$key], $matches[0][$key].$input, $text);
			}	
		}
	}
}