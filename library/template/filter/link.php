<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Link Template Filter
 *
 * Filter to parse link tags.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Template
 */
class TemplateFilterLink extends TemplateFilterTag
{
	/**
	 * Parse the text for script tags
	 *
	 * @param string $text  The text to parse
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

                $attribs = array_merge($this->parseAttributes( $matches[2][$key]), $attribs);

				$tags .= $this->_renderTag($attribs);
			}

			$text = str_replace($matches[0], '', $text);
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
		$attribs = $this->buildAttributes($attribs);

		$html = '<link '.$attribs.'/>'."\n";
		return $html;
	}
}