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
 * Dispatcher Response Transport Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
interface DispatcherResponseTransportInterface
{
    /**
     * Priority levels
     */
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH    = 2;
    const PRIORITY_NORMAL  = 3;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 5;

    /**
     * Send the response
     *
     * @param DispatcherResponseInterface $response
     * @return DispatcherResponseTransportInterface
     */
    public function send(DispatcherResponseInterface $response);

    /**
     * Get the priority of a behavior
     *
     * @return  integer The command priority
     */
    public function getPriority();
}