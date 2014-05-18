<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Passwords Database Table
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class DatabaseTablePasswords extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('behaviors' => array('expirable')));
        parent::_initialize($config);
    }
}
