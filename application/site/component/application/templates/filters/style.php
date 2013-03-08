<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Style Template Filter Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationTemplateFilterStyle extends Framework\TemplateFilterStyle
{
    public function write(&$text)
    {
        $styles = $this->_parseTags($text);
        $text = str_replace('<ktml:style />'."\n", $styles, $text);

        return $this;
    }
}