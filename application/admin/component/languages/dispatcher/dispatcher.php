<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Languages;

use Kodekit\Library;

/**
 * Default Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Languages
 */
class Dispatcher extends Library\Dispatcher
{
    public function canDispatch()
    {
        return true;
    }
}