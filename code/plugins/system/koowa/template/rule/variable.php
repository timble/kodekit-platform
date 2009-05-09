<?php
/**
* @version      $Id$
* @category		Koowa
* @package      Koowa_Template
* @subpackage	Rule
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Template rule to convert @$ and @ to $this->
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Rule 
 */
class KTemplateRuleVariable extends KObject implements KTemplateRuleInterface
{
	/**
	 * Convert '@$' and '@' to '$this->', unless when they are escaped '\@'
	 *
	 * @param string $text
	 */
	public function parse(&$text) 
	{		 
        /**
         * We could make a better effort at only finding @$ between <?php ?>
         * but that's probably not necessary as @$ doesn't occur much in the wild
         * and there's a significant performance gain by using str_replace().
         * 
         * @TODO when there is template caching, we can afford more expensive 
         * transformations
         */
		
		// Replace \@ with \$
		$text = str_replace('\@', '\$', $text);
        
        // Now replace non-eescaped @'s 
         $text = str_replace(array('@$', '@'), '$this->', $text);
        
        // Replace \$ with @
		$text = str_replace('\$', '@', $text);
	}
}
