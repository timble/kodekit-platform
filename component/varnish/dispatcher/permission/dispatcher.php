<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-varnish for the canonical source repository
 */

namespace Kodekit\Component\Varnish;

use Kodekit\Library;

/**
 * Default Dispatcher Permission
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Articles
 */
class DispatcherPermissionDispatcher extends Library\DispatcherPermissionAbstract
{
    /**
     * Permission handler for dispatch actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canDispatch()
    {
        //Only dispatch if the proxy is in the list of trusted proxies.
        if($this->getRequest()->isProxied()) {
            return true;
        }

        return false;
    }
}
