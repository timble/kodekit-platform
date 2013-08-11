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
 * Abstract Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerBehaviorAbstract extends BehaviorAbstract
{
    /**
     * Command handler
     *
     * This function translates the command name that starts with 'action' to a command handler function of the format
     * '_action[Action]'
     *
     * @param   string          $name     The command name
     * @param   CommandContext  $context  The command context
     * @return  boolean  Can return both true or false.
     */
    public function execute($name, CommandContext $context)
    {
        $this->setMixer($context->getSubject());

        $parts = explode('.', $name);
        if ($parts[0] == 'action')
        {
            $method = '_action' . ucfirst($parts[1]);

            if (method_exists($this, $method)) {
                return $this->$method($context);
            }
        }

        return parent::execute($name, $context);
    }

    /**
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a function of format _action[Action]
     *
     * @param ObjectMixable $mixer The mixer requesting the mixable methods.
     * @return array An array of methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);

        foreach ($this->getMethods() as $method)
        {
            if (substr($method, 0, 7) == '_action') {
                $methods[strtolower(substr($method, 7))] = $this;
            }
        }

        return $methods;
    }
}