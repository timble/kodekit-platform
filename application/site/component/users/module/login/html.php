<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Users;

use Kodekit\Library;
use Kodekit\Component\Pages;

/**
 * Module Login Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Users
 */
class ModuleLoginHtml extends Pages\ModuleAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->layout = $this->getObject('user')->isAuthentic() ? 'logout' : 'login';

        parent::_initialize($config);
    }
}