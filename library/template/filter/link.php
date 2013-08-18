<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Link Template Filter
 *
 * Filter to parse link tags.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
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