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
 * Decorator Template Filter
 *
 * Replace <ktml:content> with the view contents allowing to the template to act as a view decorator.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterDecorator extends TemplateFilterAbstract implements TemplateFilterRenderer
{
    /**
	 * Replace <ktml:content> with the view content
	 *
	 * @param string $text  The text to parse
	 * @return void
	 */
	public function render(&$text)
	{
        $matches = array();
        if(preg_match_all('#<ktml:content(.*)>#iU', $text, $matches))
        {
            foreach($matches[1] as $key => $match) {
                $text = str_replace($matches[0][$key], $this->getTemplate()->getView()->getContent(), $text);
            }
        }
	}
}
