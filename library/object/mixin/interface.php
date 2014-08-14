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
 * Object Mixes Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
interface ObjectMixinInterface extends ObjectHandlable
{
	/**
     * Get the mixer object
     *
     * @return ObjectMixable	The mixer object
     */
    public function getMixer();

    /**
     * Set the mixer object
     *
     * @param ObjectMixable $mixer The mixer object
     * @return ObjectMixinInterface
     */
    public function setMixer(ObjectMixable $mixer);

    /**
     * Mixin Notifier
     *
     * This function is called when the mixin is being mixed. It will get the mixer passed in.
     *
     * @param ObjectMixable $mixer The mixer object
     * @return void
     */
    public function onMixin(ObjectMixable $mixer);

    /**
     * Get a list of all the available methods
     *
     * @return array An array
     */
    public function getMethods();

    /**
     * Get the methods that are available for mixin.
     *
     * A mixable method is returned as a associative array() where the key holds the method name and the value can either
     * be an Object, a Closure or a Value.
     *
     * - Value   : If a Value is passed it will be returned, when invoking the method
     * - Object  : If an Object is passed the method will be invoke on the object and the result returned
     * - Closure : If a Closure is passed the Closure will be invoked and the result returned.
     *
     * @param  array $exclude An array of methods to be exclude
     * @return array An array of  methods
     */
    public function getMixableMethods($exclude = array());
}