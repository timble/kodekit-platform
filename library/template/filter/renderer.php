<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Renderer Template Filter Interface
 *
 * Filter will parse and render to the template to an HTML string
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
 */
interface TemplateFilterRenderer
{
    /**
     * Parse the text and render it
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function render(&$text);
}