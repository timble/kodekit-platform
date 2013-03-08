<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Framework;

/**
 * Template filter to parse link tags
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class TemplateFilterLink extends TemplateFilterTag
{
	/**
	 * Parse the text for script tags
	 *
	 * @param string Block of text to parse
	 * @return string
	 */
	protected function _parseTags(&$text)
	{
		$tags = '';

		$matches = array();
		if(preg_match_all('#<link\ href="([^"]+)"(.*)\/>#iU', $text, $matches))
		{
			foreach(array_unique($matches[1]) as $key => $match)
			{
                //Set required attributes
                $attribs = array(
                    'href' => $match
                );

                $attribs = array_merge($this->_parseAttributes( $matches[2][$key]), $attribs);

				$tags .= $this->_renderTag($attribs);
			}

			$text = str_replace($matches[0], '', $text);
		}

		return $tags;
	}

    /**
     * Render the tag
     *
     * @param 	array	Associative array of attributes
     * @param 	string	The tag content
     * @return string
     */
    protected function _renderTag($attribs = array(), $content = null)
	{
		$attribs = $this->_buildAttributes($attribs);

		$html = '<link '.$attribs.'/>'."\n";
		return $html;
	}
}