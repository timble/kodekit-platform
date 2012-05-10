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
 * Default composite spam filter class.
 *
 * Includes default SPAM filters that identify users as possible SPAMMERS or SPAM BOTS.
 *
 * @author         Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Users
 */

class ComUsersFilterSpamCompositeDefault extends ComUsersFilterSpamCompositeAbstract
{
    protected function _initialize(KConfig $config) {
        $config->append(array(
            'checks' => array(
                'com://admin/users.filter.spam.honeypot',
                'com://admin/users.filter.spam.reversehoneypot',
                'com://admin/users.filter.spam.timestamp',
                'com://admin/users.filter.spam.identicalvalues',
                'com://admin/users.filter.spam.useragent',
                'com://admin/users.filter.spam.referrer',
                'com://admin/users.filter.spam.blackhost',
            )));
        parent::_initialize($config);
    }
}
