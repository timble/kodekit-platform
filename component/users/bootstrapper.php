<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Users
 */
 class Bootstrapper extends Library\ObjectBootstrapperComponent
{
     protected function _initialize(Library\ObjectConfig $config)
     {
         $config->append(array(
             'priority' => self::PRIORITY_LOW,
             'aliases'  => array(
                 'user.provider'  => 'com:users.user.provider',
             ),
             'identifiers' => array(
                 'dispatcher' => array(
                    'authenticators' => array('com:users.dispatcher.authenticator.cookie'),
                 ),
                 'user.session' => array(
                     'handler' => 'database'
                 ),
                 'lib:user.session.handler.database'  => array(
                     'table' => 'com:users.database.table.sessions'
                 )
             )
         ));

         parent::_initialize($config);
     }
}