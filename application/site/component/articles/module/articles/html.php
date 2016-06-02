<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;
use Kodekit\Component\Pages;

/**
 * Articles Module Html View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class ModuleArticlesHtml extends Pages\ModuleEntity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'parameters' => array(
                'access'    => $this->getObject('user')->isAuthentic(),
                'limit'     => 5,
                'sort'      => '-created_on',
                'published' => 1,
                'category'  => null
            ),
        ));

        parent::_initialize($config);
    }
}