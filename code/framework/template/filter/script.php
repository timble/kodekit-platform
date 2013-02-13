<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

/**
 * Template filter to parse script tags
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterScript extends KTemplateFilterTag
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
		// <script src="" />
		if(preg_match_all('#<script(?!\s+data\-inline\s*)\s+src="([^"]+)"(.*)/>#siU', $text, $matches))
		{
			foreach(array_unique($matches[1]) as $key => $match)
			{
                //Set required attributes
                $attribs = array(
                    'src' => $match
                );

                $attribs = array_merge($this->_parseAttributes( $matches[2][$key]), $attribs);
				$tags .= $this->_renderTag($attribs);
			}

			$text = str_replace($matches[0], '', $text);
		}

		$matches = array();
		// <script></script>
		if(preg_match_all('#<script(?!\s+data\-inline\s*)(.*)>(.*)</script>#siU', $text, $matches))
		{
            foreach($matches[2] as $key => $match)
			{
				$attribs = $this->_parseAttributes( $matches[1][$key]);
				$tags .= $this->_renderTag($attribs, $match);
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
        $link = isset($attribs['src']) ? $attribs['src'] : false;

		if(!$link)
		{
            $attribs = $this->_buildAttributes($attribs);

            $html  = '<script type="text/javascript" '.$attribs.'>'."\n";
			$html .= trim($content);
			$html .= '</script>'."\n";
		}
		else
        {
            unset($attribs['src']);
            $attribs = $this->_buildAttributes($attribs);

            $html = '<script type="text/javascript" src="'.$link.'" '.$attribs.'></script>'."\n";
        }

		return $html;
	}
}