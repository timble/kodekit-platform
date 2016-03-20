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
use Kodekit\Platform\Activities;

/**
 * Articles Category Activity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class ActivityCategory extends Activities\ModelEntityActivity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'object_table'  => 'categories',
            'object_column' => 'categories_category_id'
        ));

        parent::_initialize($config);
    }
}
