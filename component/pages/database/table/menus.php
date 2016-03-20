<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Menus Database Table
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Kodekit\Component\Pages
 */
class DatabaseTableMenus extends Library\DatabaseTableAbstract
{
    public function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable', 'sluggable', 'identifiable'
            )
            ));

        parent::_initialize($config);
    }
}