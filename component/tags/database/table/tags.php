<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-tags for the canonical source repository
 */

namespace Kodekit\Component\Tags;

use Kodekit\Library;

/**
 * Tags Database Table
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Tags
 */
class DatabaseTableTags extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable', 'sluggable',
            )
        ));

        parent::_initialize($config);
    }
}