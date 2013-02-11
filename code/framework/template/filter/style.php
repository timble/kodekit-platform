<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

/**
 * Template filter to parse style tags
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterStyle extends KTemplateFilterTag
{
	/**
	 * Parse the text for style tags
	 *
	 * @param 	string 	Block of text to parse
	 * @return 	string
	 */
	protected function _parseTags(&$text)
	{
		$tags = '';

		$matches = array();
		if(preg_match_all('#<style\s+src="([^"]+)"(.*)\/>#iU', $text, $matches))
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
		if(preg_match_all('#<style(.*)>(.*)<\/style>#siU', $text, $matches))
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

            $html  = '<style type="text/css" '.$attribs.'>'."\n";
			$html .= trim($content);
			$html .= '</style>'."\n";
		}
		else
        {
            unset($attribs['src']);
            $attribs = $this->_buildAttributes($attribs);

            $html = '<link type="text/css" rel="stylesheet" href="'.$link.'" '.$attribs.' />'."\n";
        }

		return $html;
	}
}