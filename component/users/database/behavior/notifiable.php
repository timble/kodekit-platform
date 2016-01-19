<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Notifiable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorNotifiable extends Library\DatabaseBehaviorAbstract
{
    /**
     * Sends a notification E-mail to the user.
     *
     * @param array $config Optional configuration array.
     * @return bool
     */
    public function notify($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $application = $this->getObject('application');
        $user        = $this->getMixer();

        $config->append(array(
            'subject' => '',
            'message' => '',
            'from_email' => $application->getConfig()->mailfrom,
            'from_name'  => $application->getConfig()->fromname))
            ->append(array('from_email' => $user->getEmail(), 'from_name' => $user->getName()));

        return \JUtility::sendMail($config->from_email, $config->from_name, $this->email, $config->subject, $config->message);
    }
}
