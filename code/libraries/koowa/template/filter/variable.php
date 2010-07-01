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
 * Template read filter to convert @ to $this->
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterVariable extends KTemplateFilterAbstract implements KTemplateFilterRead
{
	/**
	 * Convert '@' to '$this->', unless when they are escaped '\@'
	 *
	 * @param string
	 * @return KTemplateFilterVariable
	 */
	public function read(&$text) 
	{		 
        /**
         * We could make a better effort at only finding @ between <?php ?>
         * but that's probably not necessary as @ doesn't occur much in the wild
         * and there's a significant performance gain by using str_replace().
         */
		
		// Replace \@ with \$
		$text = str_replace('\@', '\$', $text);
        
        // Now replace non-eescaped @'s 
         $text = str_replace(array('@$'), '$', $text);
        
        // Replace \$ with @
		$text = str_replace('\$', '@', $text);
		
		return $this;
	}
}
