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
 * Pages Module Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Pages
 */
class ModuleMenuHtml extends Pages\ModuleEntity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'controller' => 'com:pages.controller.pages',
            'parameters' => array(
                'menu'      => 1,
                'hidden'    => 0,
                'published' => true,
            ),
        ));

        parent::_initialize($config);
    }
}

