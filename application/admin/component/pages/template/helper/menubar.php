<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Pages;

use Kodekit\Library;
use Kodekit\Component\Pages;

/**
 * Menubar Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Pages
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