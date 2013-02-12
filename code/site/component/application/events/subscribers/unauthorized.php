<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Unauthorized Event Subscriber Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationEventSubscriberUnauthorized extends KEventSubscriberAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority' => KEvent::PRIORITY_HIGH
        ));

        parent::_initialize($config);
    }

    public function onException(KEventException $event)
    {
        if($event->getException() instanceof KControllerExceptionUnauthorized)
        {
            $application = $this->getService('application');

            if($application->getRequest()->getFormat() == 'html')
            {
                $application->getRequest()->query->clear()->add(array('view' => 'session', 'tmpl' => 'login'));
                $application->setController('users')->dispatch();

                $event->stopPropagation();
            }
        }
    }
}