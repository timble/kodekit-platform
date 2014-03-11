<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Event Handler Interface
 *
 * An EventSusbcriber knows himself what events he is interested in. Classes implementing this interface may be adding
 * listeners to an EventDispatcher through the {@link subscribe()} method.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
 */
interface EventSubscriberInterface
{
    /**
     * Register one or more listeners
     *
     * @param  EventPublisherInterface $publisher
     * @param  integer                 $priority   The event priority, usually between 1 (high priority) and 5 (lowest),
     *                                 default is 3 (normal)
     * @return array An array of public methods that have been attached
     */
    public function subscribe(EventPublisherInterface $publisher, $priority = EventInterface::PRIORITY_NORMAL);

    /**
     * Unsubscribe all previously registered listeners
     *
     * @param EventPublisherInterface $dispatcher The event dispatcher
     * @return void
     */
    public function unsubscribe(EventPublisherInterface $publisher);

    /**
     * Check if the subscriber is already subscribed to the dispatcher
     *
     * @param  EventPublisherInterface $dispatcher  The event dispatcher
     * @return boolean TRUE if the subscriber is already subscribed to the dispatcher. FALSE otherwise.
     */
    public function isSubscribed(EventPublisherInterface $publisher);
}