<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Pages;

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Menubar Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Pages
 */

class TemplateHelperMenubar extends Pages\TemplateHelperMenubar
{
    public function render($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'max_level' => 2,
        ));

        return parent::render($config);
    }
}