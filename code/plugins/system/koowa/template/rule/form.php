<?php
/**
* @version      $Id$
* @category		Koowa
* @package      Koowa_Template
* @subpackage	Rule
* @copyright    Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Template rule to handle form html elements
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Rule 
 * @uses		KSecurityToken
 */
class KTemplateRuleForm extends KObject implements KTemplateRuleInterface
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
		$has_token		= strpos($text, 'KSecurityToken');
				 
        if( $contains_form && !$is_get && !$has_token) 
        {
        	$text = str_replace(
        		'</form>', 
        		KSecurityToken::render().PHP_EOL.'</form>', 
        		$text
        	);
        }
	}
}