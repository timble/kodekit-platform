<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-debugger for the canonical source repository
 */

namespace Kodekit\Component\Debug;

use Kodekit\Library;

/**
 * Application Event Subscriber
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Debug
 */
class EventSubscriberApplication extends Library\EventSubscriberAbstract
{
    public function __construct(Library\ObjectConfig $config)
    {
        //Intercept the events for profiling
        if($this->getObject('application')->getConfig()->debug)
        {
            //Profile the event dispatcher
            $this->getObject('event.dispatcher')->decorate('event.profiler');

            //Trace database queries
            $this->getObject('event.dispatcher')->addEventSubscriber('com:debug.event.subscriber.database');
        }

        parent::__construct($config);
    }
}