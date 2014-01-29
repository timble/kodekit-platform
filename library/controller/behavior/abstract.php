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
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a function of format _action[Action]
     *
     * @param  ObjectInterface $mixer       The mixer requesting the mixable methods.
     * @param  array           $exclude     An array of public methods to be exclude
     * @return array An array of methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null, $exclude = array())
    {
        $methods = parent::getMixableMethods($mixer, $exclude);

        foreach ($this->getMethods() as $method)
        {
            if (substr($method, 0, 7) == '_action') {
                $methods[strtolower(substr($method, 7))] = strtolower(substr($method, 7));
            }
        }

        return $methods;
    }
}