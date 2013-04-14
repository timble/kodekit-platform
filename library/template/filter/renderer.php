<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Template Renderer Filter Interface
 *
 * Filter will parse and render to the template to an HTML string
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Filter
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