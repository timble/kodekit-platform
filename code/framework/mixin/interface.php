<?php
/**
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Mixes a chain of command behaviour into a class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Mixin
 */
interface KMixinInterface extends KObjectHandlable
{
    /**
     * Get the methods that are available for mixin.
     *
     * Only public methods can be mixed
     *
     * @param object The mixer requesting the mixable methods.
     * @return array An array of public methods
     */
    public function getMixableMethods(KObject $mixer = null);

    /**
     * Mixin Notifier
     *
     * This function is called when the mixin is being mixed. It will get the mixer passed in.
     *
     * @param object $mixer The mixer object
     * @return KMixinInterface
     */
    public function onMixin(KObject $mixer);

	/**
     * Get the mixer object
     *
     * @return object 	The mixer object
     */
    public function getMixer();

    /**
     * Set the mixer object
     *
     * @param object The mixer object
     * @return KMixinInterface
     */
    public function setMixer($mixer);
}