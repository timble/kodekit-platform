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
 * Template filter to convert @$ and @ to $this->
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterVariable extends KObject implements KTemplateFilterInterface
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
