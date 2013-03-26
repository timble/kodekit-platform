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
 * Passwords Database Table
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class DatabaseTablePasswords extends Framework\DatabaseTableDefault
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array('behaviors' => array('expirable')));
        parent::_initialize($config);
    }
}
