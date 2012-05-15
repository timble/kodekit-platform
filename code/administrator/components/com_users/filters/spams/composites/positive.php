<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Users
 * @copyright      Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Positive composite spam filter class.
 *
 * Includes a series of SPAM filters that positevely identifies a user as a SPAMMER or SPAM BOT.
 *
 * @author         Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Users
 */

class ComUsersFilterSpamCompositePositive extends ComUsersFilterSpamCompositeAbstract
{
    protected function _initialize(KConfig $config) {
        $config->append(array(
            'checks'=> array(
                'com://admin/users.filter.spam.service.spamhaus',
                'com://admin/users.filter.spam.mxrecord',
                'com://admin/users.filter.spam.blacklist' => array('priority' => KCommand::PRIORITY_HIGHEST))));
        parent::_initialize($config);
    }
}


