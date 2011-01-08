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
 * Template filter to parse script tags
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterScript extends KTemplateFilterAbstract implements KTemplateFilterWrite
{
	/**
	 * Find any <script src="" /> or <script></script> elements and render them
	 *
	 * @param string Block of text to parse
	 * @return KTemplateFilterLink
	 */
	public function write(&$text)
	{
		//Parse the script information
		$scripts = $this->_parseScripts($text);
		
		//Prepend the script information
		$text = $scripts.$text; 
		
		return $this;
	}
	
	/**
	 * Parse the text for script tags
	 * 
	 * @param string Block of text to parse
	 * @return string
	 */
	protected function _parseScripts(&$text)
	{
		$scripts = '';
		
		$matches = array();
		if(preg_match_all('#<script\ src="([^"]+)"(.*)\/>#iU', $text, $matches))
		{
			foreach(array_unique($matches[1]) as $key => $match) 
			{
				$attribs = $this->_parseAttributes( $matches[2][$key]);
				$scripts .= $this->_renderScript($match, true, $attribs);
			}
			
			$text = str_replace($matches[0], '', $text);
		}
			
		$matches = array();
		if(preg_match_all('#<script(.*)>(.*)<\/script>#siU', $text, $matches))
		{
			foreach($matches[2] as $key => $match) 
			{
				$attribs = $this->_parseAttributes( $matches[1][$key]);
				$scripts .= $this->_renderScript($match, false, $attribs);
			}
			
			$text = str_replace($matches[0], '', $text);
		}
		
		return $scripts;
	}
	
	/**
	 * Render script information
	 * 
	 * @param string	The script information
	 * @param boolean	True, if the script information is a URL.
	 * @param array		Associative array of attributes
	 * @return string 	
	 */
	protected function _renderScript($script, $link, $attribs = array())
	{
		$attribs = KHelperArray::toString($attribs);
		
		if(!$link) 
		{
			$html  = '<script type="text/javascript" '.$attribs.'>'."\n";
			$html .= trim($script);
			$html .= '</script>'."\n";
		}
		else $html = '<script type="text/javascript" src="'.$script.'" '.$attribs.'></script>'."\n";
		
		return $html;
	}
}