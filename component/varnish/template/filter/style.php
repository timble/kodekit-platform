<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Style Template Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Application
 */
class TemplateFilterStyle extends Library\TemplateFilterStyle
{
    /**
     * Find any virtual tags and render them
     *
     * This function will return the rendered tags found in the content
     *
     * @param string $text  The text to parse
     */
    public function filter(&$text)
    {
        //Parse the tags
        return $this->_parseTags($text);
    }

    /**
     * Parse the text for style tags
     *
     * @param string $text  The text to parse
     * @return 	string
     */
    protected function _parseTags(&$text)
    {
        $tags = '';

        $matches = array();
        if(preg_match_all('#<ktml:style\s+src="([^"]+)"(.*)\/>#iU', $text, $matches))
        {
            foreach($matches[0] as $match) {
                $tags .= $match;
            }

        }

        $matches = array();
        if(preg_match_all('#<style(.*)>(.*)<\/style>#siU', $text, $matches))
        {
            foreach($matches[0] as $match) {
                $tags .= $match;
            }
        }

        return $tags;
    }
}