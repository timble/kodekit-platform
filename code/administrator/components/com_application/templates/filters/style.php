<?php
/**
 * @version     $Id: link.php -1 1970-01-01 00:00:00Z  $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Style Template Filter Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationTemplateFilterStyle extends KTemplateFilterStyle
{
    public function write(&$text)
    {
        $styles = $this->_parseTags($text);
        $text = str_replace('<ktml:styles />'."\n", $styles, $text);

        return $this;
    }
}