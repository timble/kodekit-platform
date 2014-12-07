<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Not found Event Subscriber
 *
 * @author  Dave Li <http://github.com/daveli>
 * @package Nooku\Component\Varnish
 */
class EventSubscriberNotfound extends Library\EventSubscriberAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority' => Library\Event::PRIORITY_NORMAL
        ));

        parent::_initialize($config);
    }

    public function onException(Library\EventException $event)
    {
        if($event->getException() instanceof Library\HttpExceptionNotFound)
        {
            $dispatcher = $this->getObject('dispatcher');
            $request    = $dispatcher->getRequest();

            if($request->getFormat() == 'html')
            {
                //TODO: Make this configurable.
                $dispatcher->getContext()->getSubject()->getController()->getModel()->setState(array('published' => 0));
                $dispatcher->setCacheHeaders($dispatcher->getContext());
            }
        }
    }
}