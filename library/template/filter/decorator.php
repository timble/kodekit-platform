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
 * Decorator Template Filter
 *
 * Replace <ktml:content> with the view contents allowing to the template to act as a view decorator.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Template
 */
class TemplateFilterDecorator extends TemplateFilterAbstract
{
    /**
	 * Replace <ktml:content> with the view content
	 *
	 * @param string $text  The text to parse
	 * @return void
	 */
	public function filter(&$text)
	{
        $matches = array();
        if(preg_match_all('#<ktml:content(.*)>#iU', $text, $matches))
        {
            foreach($matches[1] as $key => $match) {
                $text = str_replace($matches[0][$key], $this->getTemplate()->content(), $text);
            }
        }
	}
}
