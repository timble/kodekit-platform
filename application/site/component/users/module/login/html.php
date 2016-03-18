<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Users;

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Module Login Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Users
 */
class ModuleLoginHtml extends Pages\ModuleAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->layout = $this->getObject('user')->isAuthentic() ? 'logout' : 'login';

        parent::_initialize($config);
    }
}