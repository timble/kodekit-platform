<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Dispatcher Route
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class DispatcherRouterRoute extends Library\DispatcherRouterRoute
{
    public function toString($parts = self::FULL, $escape = null)
    {
        if(isset($this->query['component'])) {
            $this->getObject('application')->getRouter()->build($this);
        }

        return parent::toString($parts, $escape);
    }
}