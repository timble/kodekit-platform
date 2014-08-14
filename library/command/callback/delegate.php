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
 * Command Handler Delegate
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Command
 */
interface CommandCallbackDelegate
{
    /**
     * Invoke a command callback
     *
     * @param string            $method    The name of the method to be executed
     * @param CommandInterface  $command   The command
     * @return mixed Return the result of the handler.
     */
    public function invokeCommandCallback($method, CommandInterface $command);
}
