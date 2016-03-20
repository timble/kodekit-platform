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
 * Behavior Mixin Interface
 *
 * Behaviors are attached in FIFO order during construction. Behaviors are added by name and, at runtime behaviors
 * cannot be overridden by attaching a behaviors with the same.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Behavior
 */
interface BehaviorMixinInterface
{
    /**
     * Add a behavior
     *
     * @param   mixed $behavior   An object that implements BehaviorInterface, an ObjectIdentifier
     *                            or valid identifier string
     * @param   array $config    An optional associative array of configuration settings
     * @return  Object The mixer object
     */
    public function addBehavior($behavior, $config = array());

    /**
     * Check if a behavior exists
     *
     * @param   string  $name The name of the behavior
     * @return  boolean TRUE if the behavior exists, FALSE otherwise
     */
    public function hasBehavior($name);

    /**
     * Get a behavior by name
     *
     * @param  string  $name   The behavior name
     * @return BehaviorInterface
     */
    public function getBehavior($name);

    /**
     * Gets the behaviors of the table
     *
     * @return array An associative array of table behaviors, keys are the behavior names
     */
    public function getBehaviors();
}