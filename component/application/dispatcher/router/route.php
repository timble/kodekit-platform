<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

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