<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Template filter to parse script tags
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class TemplateFilterScript extends TemplateFilterTag
{
	/**
	 * Parse the text for script tags
     *
     * This function will selectively filter all script tags that don't have a type attribute defined or where the
     * type="text/javascript". If the element includes a data-inline attribute the element will not be excluded.
	 *
	 * @param string $text  The text to parse
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

                if(!isset($attribs['type'])) {
                    $attribs['type'] = 'text/javascript';
                };

                if($attribs['type'] == 'text/javascript')
                {
				    $tags .= $this->_renderTag($attribs);
                    $text = str_replace($matches[0][$key], '', $text);
                }
			}
		}

		$matches = array();
		// <script></script>
		if(preg_match_all('#<script(?!\s+data\-inline\s*)(.*)>(.*)</script>#siU', $text, $matches))
		{
            foreach($matches[2] as $key => $match)
			{
				$attribs = $this->_parseAttributes( $matches[1][$key]);

                if(!isset($attribs['type'])) {
                    $attribs['type'] = 'text/javascript';
                };

                if($attribs['type'] == 'text/javascript')
                {
                    $tags .= $this->_renderTag($attribs, $match);
                    $text = str_replace($matches[0][$key], '', $text);
                }
			}
		}

		return $tags;
	}

    /**
     * Render the tag
     *
     * @param 	array	$attribs Associative array of attributes
     * @param 	string	$content The tag content
     * @return string
     */
    protected function _renderTag($attribs = array(), $content = null)
	{
        $link = isset($attribs['src']) ? $attribs['src'] : false;

		if(!$link)
		{
            $attribs = $this->_buildAttributes($attribs);

            $html  = '<script '.$attribs.'>'."\n";
			$html .= trim($content);
			$html .= '</script>'."\n";
		}
		else
        {
            unset($attribs['src']);
            $attribs = $this->_buildAttributes($attribs);

            $html = '<script src="'.$link.'" '.$attribs.'></script>'."\n";
        }

		return $html;
	}
}