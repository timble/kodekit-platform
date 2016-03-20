<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Comments;

use Kodekit\Library;
use Kodekit\Component\Comments;

/**
 * Comment controller class.
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Kodekit\Platform\Comments
 */
abstract class ControllerComment extends Comments\ControllerComment
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('editable'),
        ));

        parent::_initialize($config);
    }
}