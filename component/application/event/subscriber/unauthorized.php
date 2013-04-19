<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Unauthorized Event Subscriber
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class EventSubscriberUnauthorized extends Library\EventSubscriberAbstract
{
    protected function _initialize(Library\Config $config)
    {
        $config->append(array(
            'priority' => Library\Event::PRIORITY_HIGH
        ));

        parent::_initialize($config);
    }

    public function onException(Library\EventException $event)
    {
        if($event->getException() instanceof Library\ControllerExceptionUnauthorized)
        {
            $application = $this->getObject('application');

            if($application->getRequest()->getFormat() == 'html')
            {
                $application->getRequest()->query->clear()->add(array('view' => 'session', 'tmpl' => 'login'));
                $application->setComponent('users')->dispatch();

                $event->stopPropagation();
            }
        }
    }
}