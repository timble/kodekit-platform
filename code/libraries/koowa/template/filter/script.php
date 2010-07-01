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
 * Template filter to parse script tags
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterScript extends KTemplateFilterAbstract implements KTemplateFilterWrite
{
	/**
	 * Find any <script src="" /> or <script></script> elements and push them into the document
	 *
	 * @param string Block of text to parse
	 * @return KTemplateFilterStyle
	 */
	public function write(&$text)
	{
		$matches = array();
		if(preg_match_all('#<script\ src="([^"]+)" (.*)\/>#iU', $text, $matches))
		{
			foreach($matches[1] as $key => $match) 
			{
				$attribs = $this->_parseAttributes( $matches[2][$key]);
				KFactory::get($this->_template->getView())->addScript($match, true, $attribs);
			}
			
			$text = str_replace($matches[0], '', $text);
		}
			
		$matches = array();
		if(preg_match_all('#<script(.*)>(.*)<\/script>#siU', $text, $matches))
		{
			foreach($matches[2] as $key => $match) 
			{
				$attribs = $this->_parseAttributes( $matches[1][$key]);
				KFactory::get($this->_template->getView())->addScript($match, false, $attribs);
			}
			
			$text = str_replace($matches[0], '', $text);
		}
		
		return $this;
	}
}