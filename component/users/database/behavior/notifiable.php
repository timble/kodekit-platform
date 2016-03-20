<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

namespace Kodekit\Component\Users;

use Kodekit\Library;

/**
 * Notifiable Database Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Users
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
