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
 * Compiler Template Filter Interface
 *
 * Filter will compile to the template to executable PHP code.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
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