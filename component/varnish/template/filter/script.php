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
 * Script Template Filter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Varnish
 */
class TemplateFilterScript extends Library\TemplateFilterScript
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
        // <ktml:script src="" />
        if(preg_match_all('#<ktml:script(?!\s+data\-inline\s*)\s+src="([^"]+)"(.*)/>#siU', $text, $matches))
        {
            foreach($matches[0] as $match) {
                $tags .= $match;
            }
        }

        $matches = array();
        // <script></script>
        if(preg_match_all('#<script(?!\s+data\-inline\s*)(.*)>(.*)</script>#siU', $text, $matches))
        {
            foreach($matches[0] as $match) {
                $tags .= $match;
            }
        }

        return $tags;
    }
}