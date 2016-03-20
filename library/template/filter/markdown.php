<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Markdown Template Filter
 *
 * Filter to parse <ktml:markdown></ktml:markdown> tags. Content should be valid markdown will be converted to html.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Template
 */
class TemplateFilterMarkdown extends TemplateFilterAbstract
{
    /**
     * Replace <ktml:markdown></ktml:markdown> and parse contained markdown to html
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function filter(&$text)
    {
        $matches = array();
        if(preg_match_all('#<ktml:markdown>(.*)<\/ktml:markdown>#siU', $text, $matches))
        {
            $engine = $this->getObject('template.engine.factory')
                ->createEngine('markdown', array('template' => $this->getTemplate()));

            foreach($matches[1] as $key => $match)
            {
                $html = $engine->loadString($matches[1][$key])->render();
                $text = str_replace($matches[0][$key], $html, $text);
            }
        }
    }
}
