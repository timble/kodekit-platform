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
 * Template Compiler Filter Interface
 *
 * Filter will parse and compile to the template to executable PHP code.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Filter
 */
interface TemplateFilterCompiler
{
    /**
     * Parse the text and compile it to PHP
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function compile(&$text);
}