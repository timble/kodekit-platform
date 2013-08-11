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
     * Event handlers always start with 'on' and need to be public methods
     * 
     * @return array An array of public methods
     */
    public function getSubscriptions();
}