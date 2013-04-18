<?php
/**
 * @package     Koowa_Object
 * @subpackage  Mixin
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Mixes a chain of command behaviour into a class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Mixin
 */
interface ObjectMixinInterface extends ObjectHandlable
{
    /**
     * Get the methods that are available for mixin.
     *
     * Only public methods can be mixed
     *
     * @param ObjectMixable $mixer The mixer requesting the mixable methods.
     * @return array An array of public methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null);

    /**
     * Mixin Notifier
     *
     * This function is called when the mixin is being mixed. It will get the mixer passed in.
     *
     * @param ObjectMixable $mixer The mixer object
     * @return ObjectMixinInterface
     */
    public function onMixin(ObjectMixable $mixer);

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
}