<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Decorator Template Filter
 *
 * Replace <ktml:content> with the view contents allowing to the template to act as a view decorator.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
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
