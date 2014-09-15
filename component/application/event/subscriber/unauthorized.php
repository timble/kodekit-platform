<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Unauthorized Event Subscriber
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Application
 */
class EventSubscriberUnauthorized extends Library\EventSubscriberAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority' => Library\Event::PRIORITY_HIGH
        ));

        parent::_initialize($config);
    }

    public function onException(Library\EventException $event)
    {
        if($event->getException() instanceof Library\HttpExceptionUnauthorized)
        {
            $application = $this->getObject('application');
            $request     = $application->getRequest();
            $response    = $application->getResponse();

            if($request->getFormat() == 'html')
            {
                if($request->isSafe())
                {
                    $request->query->clear()->add(array('view' => 'session', 'tmpl' => 'login'));
                    $application->forward('users');
                }
                else $response->setRedirect($request->getReferrer(), $event->getMessage(), 'error');

                $application->dispatch();

                //Stop event propagation
                $event->stopPropagation();
            }
        }
    }
}