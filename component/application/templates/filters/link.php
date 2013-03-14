<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Application;

use Nooku\Framework;

/**
 * Link Template Filter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class TemplateFilterLink extends Framework\TemplateFilterLink
{
    public function write(&$text)
    {
        $links = $this->_parseTags($text);
        $text = str_replace('<ktml:link />'."\n", $links, $text);

        return $this;
    }
}