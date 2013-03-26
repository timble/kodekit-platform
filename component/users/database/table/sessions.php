<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Users;

use Nooku\Framework;

/**
 * Sessions Database Table
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Users
 */
class DatabaseTableSessions extends Framework\DatabaseTableDefault
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'identity_column' => 'users_session_id',
        ));

        parent::_initialize($config);
    }
}