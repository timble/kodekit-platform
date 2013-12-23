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
 * An EventSubscriber knows himself what events he is interested in. If an EventSubscriber is added to an
 * EventDispatcherInterface, the dispatcher invokes {@link getListeners} and registers the subscriber
 * as a listener for all returned events.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
 */
interface EventSubscriberInterface
{
    /**
     * Get the priority of the subscriber
     *
     * @return	integer The event priority
     */
    public function getPriority();
          
    /**
     * Get a list of subscribed events
     *
     * The array keys are event names and the value is an associative array composed of a callable and an optional
     * priority. If no priority is defined the dispatcher is responsible to set a default.
     *
     * eg  array('eventName' => array('calla' => array($object, 'methodName'), 'priority' => $priority))
     *
     * @return array The event names to listen to
     */
    public function getListeners();
}