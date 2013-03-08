<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Unauthorized Event Subscriber Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationEventSubscriberUnauthorized extends Framework\EventSubscriberAbstract
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'priority' => Framework\Event::PRIORITY_HIGH
        ));

        parent::_initialize($config);
    }

    public function onException(Framework\EventException $event)
    {
        if($event->getException() instanceof Framework\ControllerExceptionUnauthorized)
        {
            $application = $this->getService('application');

            if($application->getRequest()->getFormat() == 'html')
            {
                $application->getRequest()->query->clear()->add(array('view' => 'session', 'tmpl' => 'login'));
                $application->setComponent('users')->dispatch();

                $event->stopPropagation();
            }
        }
    }
}