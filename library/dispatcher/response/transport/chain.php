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
 * Chain Template Filter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Response
 */
class DispatcherResponseTransportChain extends ObjectQueue implements DispatcherResponseTransportInterface
{
    /**
     * Send the HTTP response by running through the transport chain.
     *
     * If a transport handler return true the chain will be stopped.
     *
     * @return boolean  Returns true if the response has been send, otherwise FALSE
     */
    public function send()
    {
        foreach($this as $transport)
        {
            if($transport instanceof DispatcherResponseTransportInterface)
            {
                if($transport->send() === TRUE) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Attach a transport to the queue
     *
     * The priority parameter can be used to override the filter priority while enqueueing the filter.
     *
     * @param   DispatcherResponseTransportInterface  $transport
     * @param   integer          $priority The transport priority, usually between 1 (high priority) and 5 (lowest),
     *                                     default is 3. If no priority is set, the transport priority will be used
     *                                     instead.
     * @return DispatcherResponseTransportChain
     * @throws \InvalidArgumentException if the object doesn't implement DispatcherResponseTransportInterface
     */
    public function enqueue(ObjectHandlable $transport, $priority = null)
    {
        if (!$transport instanceof DispatcherResponseTransportInterface) {
            throw new \InvalidArgumentException('Transport needs to implement DispatcherResponseTransportInterface');
        }

        $priority = is_int($priority) ? $priority : $transport->getPriority();
        return parent::enqueue($transport, $priority);
    }

    /**
     * Removes a transport from the queue
     *
     * @param   DispatcherResponseTransportInterface   $transport
     * @return  boolean    TRUE on success FALSE on failure
     * @throws \InvalidArgumentException if the object doesn't implement DispatcherResponseTransportInterface
     */
    public function dequeue(ObjectHandlable $transport)
    {
        if (!$transport instanceof DispatcherResponseTransportInterface) {
            throw new \InvalidArgumentException('Transport needs to implement DispatcherResponseTransportInterface');
        }

        return parent::dequeue($transport);
    }

    /**
     * Check if the queue does contain a given filter
     *
     * @param  DispatcherResponseTransportInterface   $transport
     * @return bool
     * @throws \InvalidArgumentException if the object doesn't implement DispatcherResponseTransportInterface
     */
    public function contains(ObjectHandlable $transport)
    {
        if (!$transport instanceof TemplateFilterInterface) {
            throw new \InvalidArgumentException('Transport needs to implement DispatcherResponseTransportInterface');
        }

        return parent::contains($transport);
    }
}