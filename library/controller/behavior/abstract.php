<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Abstract Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Controller
 */
abstract class ControllerBehaviorAbstract extends BehaviorAbstract
{
    /**
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a function of format _action[Action]
     *
     * @param  array $exclude     An array of public methods to be exclude
     * @return array An array of methods
     */
    public function getMixableMethods($exclude = array())
    {
        $methods = parent::getMixableMethods($exclude);

        if($this->isSupported())
        {
            foreach($this->getMethods() as $method)
            {
                if(substr($method, 0, 7) == '_action') {
                    $methods[strtolower(substr($method, 7))] = $this;
                }
            }
        }

        return $methods;
    }

    /**
     * Command handler
     *
     * @param CommandInterface         $command    The command
     * @param CommandChainInterface    $chain      The chain executing the command
     * @return mixed If a handler breaks, returns the break condition. Returns the result of the handler otherwise.
     */
    public function execute(CommandInterface $command, CommandChainInterface $chain)
    {
        $parts  = explode('.', $command->getName());
        $method = '_'.$parts[0].ucfirst($parts[1]);

        if($parts[0] == 'action') {
            $result = $this->$method($command);
        } else {
            $result = parent::execute($command, $chain);
        }

        return $result;
    }
}