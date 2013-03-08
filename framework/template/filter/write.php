<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Framework;

/**
 * Template Write Filter Interface
 *
 * Process the template on output
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Filter
 */
interface TemplateFilterWrite
{
    /**
     * Parse the text and filter it
     *
     * @param string Block of text to parse
     * @return TemplateFilterWrite
     */
    public function write(&$text);
}