<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Event Subscriber
 *
 * An EventSusbcriber knows himself what events he is interested in. Classes extending the abstract implementation may
 * be adding listeners to an EventDispatcher through the {@link subscribe()} method.
 *
 * Listeners must be public class methods following a camel Case naming convention starting with 'on', eg onFooBar. The
 * listener priority is usually between 1 (high priority) and 5 (lowest), default is 3 (normal)
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Event
 */
abstract class EventSubscriberAbstract extends Object implements EventSubscriberInterface
{
    /**
     * List of event listeners
     *
     * @var array
     */
    private $__listeners;

    /**
     * Attach one or more listeners
     *
     * Event listeners always start with 'on' and need to be public methods.
     *
     * @param  EventPublisherInterface $publisher
     * @param  integer                 $priority   The event priority, usually between 1 (high priority) and 5 (lowest),
     *                                 default is 3 (normal)
     * @return array An array of public methods that have been attached
     */
    public function subscribe(EventPublisherInterface $publisher, $priority = Event::PRIORITY_NORMAL)
    {
        $handle = $publisher->getHandle();

        if(!$this->isSubscribed($publisher));
        {
            //Get all the public methods
            $reflection = new \ReflectionClass($this);
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method)
            {
                if(substr($method->name, 0, 2) == 'on')
                {
                    $publisher->addListener($method->name, array($this, $method->name), $priority);
                    $this->__listeners[$handle][] = $method->name;
                }
            }
        }

        return $this->__listeners;
    }

    /**
     * Detach all previously attached listeners for the specific dispatcher
     *
     * @param EventPublisherInterface $publisher
     * @return void
     */
    public function unsubscribe(EventPublisherInterface $publisher)
    {
        $handle = $publisher->getHandle();

        if($this->isSubscribed($publisher));
        {
            foreach ($this->__listeners[$handle] as $index => $listener)
            {
                $publisher->removeListener($listener, array($this, $listener));
                unset($this->__listeners[$handle][$index]);
            }
        }
    }

    /**
     * Check if the subscriber is already subscribed to the dispatcher
     *
     * @param  EventPublisherInterface $dispatcher  The event dispatcher
     * @return boolean TRUE if the subscriber is already subscribed to the dispatcher. FALSE otherwise.
     */
    public function isSubscribed(EventPublisherInterface $publisher)
    {
        $handle = $publisher->getHandle();
        return isset($this->__listeners[$handle]);
    }
}